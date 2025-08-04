<?php

$docRoot = $_SERVER['DOCUMENT_ROOT'];
$phpRoot =  $docRoot === '/Library/WebServer/Documents' ? $docRoot . '/MyWebPages/PHP/' : $docRoot . '/../PHP/';
$_COOKIE['phpPath'] = $phpRoot;

echo $phpRoot;


include_once $phpRoot . 'all.php';

list($html, $head, $body) = makePage('Happy Anniversary');
$topBar = makeTopBar();
$topBar->addChildren([
    makePageTitle("Darling Happy Anniversary")
]);


