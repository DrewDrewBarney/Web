<?php

$url = "https://run.drewshardlow.com/RunningSite/Pages/physiology.php";

/* ---------- helpers ---------- */

function resolveUrl(string $base, string $rel): string {
    if ($rel === '')
        return $base;
    if (parse_url($rel, PHP_URL_SCHEME))
        return $rel;
    if (strpos($rel, '//') === 0) {
        $scheme = parse_url($base, PHP_URL_SCHEME) ?: 'https';
        return $scheme . ':' . $rel;
    }
    $bp = parse_url($base);
    $scheme = $bp['scheme'] ?? 'https';
    $host = $bp['host'] ?? '';
    $port = isset($bp['port']) ? ':' . $bp['port'] : '';
    $path = $bp['path'] ?? '/';
    if (substr($path, -1) !== '/')
        $path = preg_replace('~[^/]+$~', '', $path);
    $path = ($rel[0] ?? '') === '/' ? $rel : $path . $rel;
    $out = [];
    foreach (explode('/', $path) as $seg) {
        if ($seg === '' || $seg === '.')
            continue;
        if ($seg === '..') {
            array_pop($out);
            continue;
        }
        $out[] = $seg;
    }
    return "$scheme://$host$port/" . implode('/', $out);
}

function fetch(string $u): ?string {
    $ch = curl_init($u);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => "Mozilla/5.0 (compatible; MyScraper/1.0)",
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 20,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($body === false || $code >= 400) ? null : $body;
}

/* ---------- CSS scoper (ID prefix) ---------- */

function scopeSelectors(string $selectors, string $scopeId): string {
    $parts = [];
    $buf = '';
    $depth = 0;
    $L = strlen($selectors);
    for ($i = 0; $i < $L; $i++) {
        $ch = $selectors[$i];
        if ($ch === '(' || $ch === '[')
            $depth++;
        if ($ch === ')' || $ch === ']')
            $depth--;
        if ($ch === ',' && $depth === 0) {
            $parts[] = trim($buf);
            $buf = '';
            continue;
        }
        $buf .= $ch;
    }
    if ($buf !== '')
        $parts[] = trim($buf);

    $prefix = "#$scopeId";
    $out = [];
    foreach ($parts as $sel) {
        $s = preg_replace(
                '/^\s*(?:(?:html|body|:root)\s*)+((?:[>+~]|\s|$).*)$/i',
                $prefix . '${1}',
                $sel, 1, $count
        );
        if ($count === 0)
            $s = $prefix . ' ' . $sel;
        $out[] = $s;
    }
    return implode(', ', $out);
}

function scopeCss(string $css, string $scopeId): string {
    // strip comments
    $css = preg_replace('~/\*.*?\*/~s', '', $css);

    $out = '';
    $i = 0;
    $n = strlen($css);

    while ($i < $n) {
        $brace = strpos($css, '{', $i);
        if ($brace === false) {
            $out .= substr($css, $i);
            break;
        }

        $prelude = trim(substr($css, $i, $brace - $i));
        $i = $brace + 1;

        // find matching }
        $depth = 1;
        $j = $i;
        while ($j < $n && $depth > 0) {
            $c = $css[$j];
            if ($c === '{') $depth++;
            elseif ($c === '}') $depth--;
            $j++;
        }
        $block = substr($css, $i, $j - $i - 1);
        $i = $j;

        // Clean up property lines
        $lines = explode("\n", $block);
        $cleanLines = [];
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed !== '') {
                $cleanLines[] = '    ' . $trimmed; // single consistent indent
            }
        }
        $formattedBlock = implode("\n", $cleanLines);

        if (preg_match('/^@(?:media|supports)\b/i', $prelude)) {
            $nested = scopeCss($block, $scopeId);
            $out .= $prelude . " {\n" . $nested . "\n}\n\n";

        } elseif (preg_match('/^@(?:keyframes|font-face|page|counter-style)\b/i', $prelude)) {
            $out .= $prelude . " {\n" . $formattedBlock . "\n}\n\n";

        } else {
            $scopedSelector = scopeSelectors($prelude, $scopeId);
            $out .= $scopedSelector . " {\n" . $formattedBlock . "\n}\n\n";
        }
    }

    return rtrim($out) . "\n";
}


/* ---------- step 1: fetch HTML ---------- */
$html = fetch($url);
if ($html === null) {
    die("Failed to fetch $url\n");
}

/* ---------- step 2: parse ---------- */
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);
$baseHref = $xpath->evaluate("string(//base/@href)");
$base = $baseHref ? resolveUrl($url, $baseHref) : $url;

$combinedCSS = "";

/* ---------- step 3a: gather linked CSS ---------- */
$links = $xpath->query("//link[
    translate(@rel,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz')='stylesheet' or
    (translate(@rel,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz')='preload' and
     translate(@as,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz')='style')
]");
foreach ($links as $lnk) {
    $href = $lnk->getAttribute('href');
    if (!$href)
        continue;
    $cssUrl = resolveUrl($base, $href);
    if (($css = fetch($cssUrl)) !== null) {
        $combinedCSS .= "\n/* From: $cssUrl */\n$css\n";
    }
    $lnk->parentNode->removeChild($lnk);
}

/* ---------- step 3b: inline <style> + follow @import ---------- */

function importUrls(string $css): array {
    if (!preg_match_all('~@import\s+(?:url\()?["\']?([^"\')\s]+)["\']?\)?\s*[^;]*;~i', $css, $m))
        return [];
    return $m[1];
}

$styleNodes = $xpath->query("//style");
foreach ($styleNodes as $st) {
    $css = $st->textContent ?? '';
    if (trim($css) !== '') {
        $combinedCSS .= "\n/* From inline <style> */\n$css\n";
        foreach (importUrls($css) as $rel) {
            $cssUrl = resolveUrl($base, $rel);
            if (($imp = fetch($cssUrl)) !== null) {
                $combinedCSS .= "\n/* From: $cssUrl (via @import) */\n$imp\n";
            }
        }
    }
}

/* ---------- step 3c: wrap body content ---------- */
$body = $dom->getElementsByTagName('body')->item(0);
$norm = preg_replace(['~/\*.*?\*/~s', '/\s+/'], ['', ' '], trim($combinedCSS));
$scopeId = 'legacy_' . substr(md5($norm), 0, 12);

if ($body && $body->firstChild) {
    $wrapper = $dom->createElement('section');
    $wrapper->setAttribute('id', $scopeId);
    while ($body->firstChild) {
        $wrapper->appendChild($body->firstChild);
    }
    $body->appendChild($wrapper);
}

/* ---------- step 4: build final (scoped) CSS + minimal shims ---------- */
$scopedCss = scopeCss($combinedCSS, $scopeId);

// Minimal anti-leak shims (tweak as needed)
$shims = <<<CSS
/* minimal anti-leak shims (site → legacy) */
#$scopeId aside { all: unset; display: block; margin: 1em 0; }
#$scopeId .equationTable { margin-left: auto; margin-right: auto; } /* center equations */
CSS;

$finalCss = "/* SCOPE: #$scopeId */\n" . $shims . "\n" . $scopedCss;

/* ---------- step 5: inject CSS LAST in <head> ---------- 
  if (trim($finalCss) !== '') {
  $styleTag = $dom->createElement('style', $finalCss);
  $styleTag->setAttribute('type', 'text/css');

  $head = $dom->getElementsByTagName('head')->item(0);
  if (!$head) {
  $head = $dom->createElement('head');
  $dom->documentElement->insertBefore($head, $dom->documentElement->firstChild);
  }
  // make sure <meta charset> is present and first
  $meta = $xpath->query('//head/meta[@charset]')->item(0);
  if (!$meta) {
  $meta = $dom->createElement('meta');
  $meta->setAttribute('charset', 'UTF-8');
  $head->insertBefore($meta, $head->firstChild);
  }
  // append last so legacy CSS wins by order
  $head->appendChild($styleTag);
  } else {
  file_put_contents("debug-original.html", $html);
  file_put_contents("debug-parsed.html", $dom->saveHTML());
  echo "Warning: no CSS found to scope.\n";
  }
 * 
 */

/* ---------- step 6: save ---------- */
file_put_contents("legacyPage.html", $dom->saveHTML());
file_put_contents("legacyCSS.css", $finalCss);
echo "Saved page and CSS\n";
