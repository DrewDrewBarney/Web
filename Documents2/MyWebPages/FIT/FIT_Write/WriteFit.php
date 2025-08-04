<?php

include_once '../../Common/PHP/all.php';
include_once 'WritingFileHeader.php';
include_once 'WritingDefinitionRecord.php';

$recordBytes = [];
$recordsSize = 0;
for ($globalMsgNo = 0; $globalMsgNo < 500; $globalMsgNo++) {
    $record = new WritingDefinitionRecord(['globalMsgNo' => $globalMsgNo]);
    $recordBytes = array_merge($recordBytes, $record->toBytes());
    $recordsSize += $record->size();
}

$header = new WritingFileHeader($recordsSize);
$headerPlusRecords = array_merge($header->toBytes(), $recordBytes);
$crc = WritingTools::getCRC($headerPlusRecords);

$fileBytesArray = array_merge($headerPlusRecords, $crc);

$bufferString = '';
foreach ($fileBytesArray as $byte) {
    $bufferString .= chr($byte);
}

echo $bufferString;
echo strlen($bufferString);

$filename = 'Written FIT files/min.fit';
$mode = 'w';
try {
    $stream = fopen($filename, $mode);
    fwrite($stream, $bufferString);
    fclose($stream);
} catch (Exception $e) {
    echo 'failed to write file';
}
   

