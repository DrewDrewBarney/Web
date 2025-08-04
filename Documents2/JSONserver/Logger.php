<?php
include_once 'LoggingFunctions.php';


function mainLogger() {
    $headersIn = apache_request_headers();
    if (key_exists("Error", $headersIn)) {
        appendLog($headersIn['Error']);
    } 
}

// entry point to execution when page is invoked by http request
mainLogger();
