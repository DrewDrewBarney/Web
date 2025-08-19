<?php

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

function traverseTags(DOMElement $node, $tab) {
    $tagName = $node->tagName;
    echo $tab . $tagName . "\n";
    //$attributes = getAttributes($node);
    //$tag = new Tag($tagName, $attributes);
    // normal flow for all other elements
    foreach ($node->childNodes as $childNode) {
        switch ($childNode->nodeType) {
            case XML_ELEMENT_NODE:
                traverseTags($childNode, '  ' . $tab);
                break;
            case XML_TEXT_NODE:
            case XML_CDATA_SECTION_NODE:
                $text = trim($childNode->nodeValue);

                echo $text !== '' ? '    ' . $tab . $text . "\n" : '';
                break;
        }
    }
}

$html = fetch('https://run.drewshardlow.com/RunningSite/Pages/home.php');

$dom = new DOMDocument();
$dom->loadHTML($html);
$root = $dom->documentElement;
echo '<pre>';
traverseTags($root, '');
