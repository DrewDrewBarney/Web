<?php

function appendLog($message) {
    $error = date("Y-m-d  h:i:sa  ") . $message . "\n";
    $logFileName = 'MyLogging/GarminWatchErrors.txt';
    $handle = fopen($logFileName, 'at');
    if ($handle !== false) {
        fwrite($handle, '.');
        fwrite($handle, $error);
        fclose($handle);
    }
}


