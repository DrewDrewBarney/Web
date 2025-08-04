<?php

include_once '../../Documents/Common/PHP/Tag.php';

$dom = new DOMDocument();
libxml_use_internal_errors(true); // Suppress HTML5 warnings

$path = '../Documents2/MyWebPages/RunningSite/Pages/physiology.php';
$html = file_get_contents($path); // Or use a string of HTML
$dom->loadHTML($html);

libxml_clear_errors(); // Clear warnings after parsing

$paras = $dom->getElementsByTagName('p')->item(0);

/*
  function getInnerHTML(DOMElement $element) {
  $innerHTML = '';
  foreach ($element->childNodes as $child) {
  $innerHTML .= $element->ownerDocument->saveHTML($child);
  }
  return $innerHTML;
  }
 * 
 */

function getInnerText(DOMnode $node): string {
    foreach ($node->childNodes as $child) {
        if ($child instanceof DOMText) {
            return $child->wholeText;
        }
    }
    return '';
}

function getAttributes(DOMElement $node): array {
    $attrs = [];
    if ($node->hasAttributes()) {
        foreach ($node->attributes as $attr) {
            $attrs[$attr->name] = $attr->value;
        }
    }
    return $attrs;
}

function traverseTags(DOMNode $node, Tag $parent) {
    if ($node instanceof DOMElement) {
        $tagName = $node->tagName;
        $inner = getInnerText($node);
        $attributes = getAttributes($node);
       $childTag = $parent->makeChild($tagName, $inner, $attributes);
    }

    foreach ($node->childNodes as $child) {
        traverseTags($child, $childTag);
    }
}

$root = Tag::make('html');
traverseTags($dom->documentElement, $root);

//$root->render();

foreach($root->toPHP() as $line){
    echo $line;
}

