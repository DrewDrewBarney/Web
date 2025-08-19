
<?php

include_once '../../Documents/Common/PHP/Tag.php';

$dom = new DOMDocument();
libxml_use_internal_errors(true); // Suppress HTML5 warnings

$path = 'page-inline-css.html';
$html = file_get_contents($path); // Or use a string of HTML
$dom->loadHTML($html);
$dom->normalize();

libxml_clear_errors(); // Clear warnings after parsing

function getAttributes(DOMElement $node): array {
    $attrs = [];
    if ($node->hasAttributes()) {
        foreach ($node->attributes as $attr) {
            $attrs[$attr->name] = $attr->value;
        }
    }
    return $attrs;
}

function traverseTags(DOMElement $node): Tag {
    $tagName = strtolower($node->tagName);
    $attributes = getAttributes($node);
    $tag = new Tag($tagName, $attributes);
    
    // normal flow for all other elements
    foreach ($node->childNodes as $childNode) {
        switch ($childNode->nodeType) {
            case XML_ELEMENT_NODE:
                $tag->addChild(traverseTags($childNode));
                break;
            case XML_TEXT_NODE:
            case XML_CDATA_SECTION_NODE:
                // keep compact for normal text; do NOT trim inside style/script (handled above)
                $s = trim($childNode->nodeValue);
                if ($s !== '') $tag->addText($s);
                break;
        }
    }
    return $tag;
}


$root = traverseTags($dom->documentElement);

$root->render();

foreach ($root->toPHP() as $line) {
    echo $line;
}


