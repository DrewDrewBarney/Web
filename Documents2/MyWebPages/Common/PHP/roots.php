<?php


// server side document root
function serverDocumentRoot(){
    $docRoot = $_SERVER['DOCUMENT_ROOT'] . '/';
    return $docRoot === '/Library/WebServer/Documents/' ? $docRoot . 'MyWebPages/' : $docRoot;
}

//echo 'server document root is ' . serverDocumentRoot();

function clientDocumentRoot(){
    $docRoot = $_SERVER['SERVER_NAME'] . '/';
    return $docRoot === 'localhost/' ? 'http://' . $docRoot . 'MyWebPages/' : 'https://' . $docRoot;
}
