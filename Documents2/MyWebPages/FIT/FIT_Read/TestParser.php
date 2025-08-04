<?php

include_once 'ParseFit.php';

$filename = 'basic.fit';
//$filename = 'realRun.fit';
$parser = new ParseFit($filename);
$parser->parse();