<?php

include_once 'FileHeader.php';
include_once 'RecordHeader.php';
include_once 'DefinitionRecord.php';
include_once 'DataRecord.php';
include_once 'DefinitionField.php';
include_once 'FITdebug.php';

class ParseFit {

    // PARSING STUFF
    var array $buffer = [];
    var int $cursor = 0;
    var bool $error = false;
    var string $errorMsg = 'OK';
    //
    // FILE HEADER BYTE VALUES
    var ?FileHeader $fileHeader = null;
    var int $fileHeaderSize = 0;
    var array $definitionRecords = [];
    var array $globalMessageNumbers = [];
    // DATE OBTAINED
    var array $tables = [];

    function __construct(string $filePath) {
        $this->error = 0;
        $this->buffer = [];

        $filesize = filesize($filePath);
        $file = fopen($filePath, 'rb');
        $bufferString = fread($file, $filesize);
        fclose($file);
        
        FITdebug::echo($filePath);
        FITdebug::echo($filesize);

        $fitSignature = substr($bufferString, 8, 4);

        if ($fitSignature == '.FIT') {
            for ($i = 0; $i < strlen($bufferString); $i++) {
                $byte = ord($bufferString[$i]);
                if ($byte < 0 || $byte > 255) {
                    throw new Exception("not really a byte");
                    $this->setError('Failed initial file to byte buffer conversion with negative value');
                }
                $this->buffer[] = $byte;
            }
        } else {
            $this->setError('Not a FIT file');
        }
    }

    function reset(): void {
        $this->cursor = 0;
        $this->error = false;
        $this->errorMsg = 'OK';
    }

    function setError(string $msg): void {
        $this->error = true;
        $this->errorMsg = $msg;
        //throw new Exception($this->errorMsg);
    }
    
    function inError():bool{
        return $this->error;
    }

    function peekByte(): int {   
        if (isset($this->buffer[$this->cursor])){
            return $this->buffer[$this->cursor];
        } else {
            $this->setError('premature end of file');
            return -1;
        }
    }

    function getByte(): int {
        $result = $this->peekByte();
        $this->cursor++;
        return $result;
    }

    function getBytes(int $len): array {
        $result = array_slice($this->buffer, $this->cursor, $len);
        $this->cursor += $len;
        return $result;
    }

    function endOfRecords(): bool {
        return $this->fileHeader ? $this->cursor >= $this->fileHeader->dataSize + $this->fileHeaderSize : true;
    }

    function positionCursor(int $cursor): void {
        $this->cursor = $cursor;
    }

    function bytesToInt(array $bytes, array $attributes): int {

        $big = isset($attributes['bigEndian']) ? $attributes['bigEndian'] : false;
        $signed = isset($attributes['signed']) ? $attributes['signed'] : false;

        $bytes = $big ? array_reverse($bytes) : $bytes;
        $result = 0;
        $base = 1;
        foreach ($bytes as $byte) {
            $result += $base * $byte;
            $base <<= 8;
        }

        // negative 2s complement
        $signBit = $base >> 1;
        $mask = $base - 1; // from 1000... to FFF...

        if ($signed && ($result & $signBit)) {

            //$a = strtoupper(dechex($signBit));
            //$b = strtoupper(dechex($result));
            //$c = strtoupper(dechex($mask));
            //$result = -(($result ^ $mask) + 1);
            $result = -(($result - 1) ^ $mask); // change twos complement representation of negative integer to a negative integer
        }

        return intval($result);
    }

    function parse(): array {
        if ($this->error){ return []; }
        $style = 'font-family:Sans-serif;font-size:1.2rem;';
        FITdebug::echo( "<body style = $style>");
        $this->parseHeader();
        FITdebug::echo('<h1>' . $this->errorMsg . '</h1>');
        FITdebug::echo('</body>');

        /*
          foreach ($this->globalMessageNumbers as $num) {
          echo "$num<br>";
          }
         */

        //$this->showTable('record');

        return $this->tables;
    }

    function parseHeader(): void {
        $this->fileHeaderSize = $this->peekByte();
        $this->fileHeader = new FileHeader($this->getBytes($this->fileHeaderSize));
        $this->fileHeader->show();
        $this->parseRecordHeader();
    }

    function parseRecordHeader(): void {

        while (!$this->endOfRecords() && !$this->error) {

            $recordHeader = new RecordHeader($this->getByte());
            //$recordHeader->show();

            if ($recordHeader->recordHeaderType == RecordHeader::RECORD_HEADER_TYPE_NORMAL) {
                if ($recordHeader->messageType == RecordHeader::RECORD_HEADER_MESSAGE_TYPE_DEFINITION) {
                    $this->parseDefinitionRecord($recordHeader);
                } else if ($recordHeader->messageType == RecordHeader::RECORD_HEADER_MESSAGE_TYPE_DATA) {
                    $this->parseDataRecord($recordHeader);
                }
            } else if ($recordHeader->recordHeaderType == RecordHeader::RECORD_HEADER_TYPE_TIME) {
                $this->parseDataRecord($recordHeader);
            }
        }
    }

    function parseFieldDescriptors(): array {
        $numberOfFields = $this->getByte();
        $fieldDescriptors = [];
        for ($i = 0; $i < $numberOfFields; $i++) {
            $fieldDescriptors[] = $this->getBytes(3);
        }
        return $fieldDescriptors;
    }

    function parseDefinitionRecord(RecordHeader $recordHeader): void {
        $reserved = $this->getByte();
        $architecture = $this->getByte();

        $globalMessageNumber = $this->bytesToInt($this->getBytes(2), ['bigEndian' => $architecture]);

        $this->globalMessageNumbers[] = $globalMessageNumber;

        $fieldDescriptors = $this->parseFieldDescriptors();
        if ($recordHeader->messageTypeSpecific) { // developer fields present
            $fieldDescriptors = array_merge($fieldDescriptors, $this->parseFieldDescriptors());
        }

        $definitionRecord = new DefinitionRecord($this, $architecture, $globalMessageNumber, $fieldDescriptors);
        $this->definitionRecords[$recordHeader->localMessageType] = $definitionRecord;

        // show the definition record, color coded
        $backgroundColor = $recordHeader->backgroundColor($globalMessageNumber);
        $recordHeader->show($backgroundColor);
        $definitionRecord->show($backgroundColor);
    }

    function parseDataRecord(RecordHeader $recordHeader): void {

        $this->globalMessageNumbers[] = '-';

        if (isset($this->definitionRecords[$recordHeader->localMessageType])) { // check if a definition record exists which defines this data record or raise error
            $definitionRecord = $this->definitionRecords[$recordHeader->localMessageType];
            $dataRecord = $definitionRecord->makeDataRecord();

            // show the datarecord
            //$backgroundColor = $recordHeader->backgroundColor();
            //$recordHeader->show();
            $dataRecord->show($recordHeader->backgroundColor($definitionRecord->globalMessageNumber));

            $this->storeRecord($dataRecord);
        } else {
            $this->setError('Unrecognised type of datarecord as it has not been introduced via a record header localmessagetype with datadefinition.');
        }
    }

    function storeRecord(DataRecord $dataRecord): void {
        $record = [];
        foreach ($dataRecord->values as $key => $value) {
            $record[$key] = $value;
        }
        $this->tables[$dataRecord->globalMessageName][] = $record;
    }

    function tables(): array {
        return $this->tables;
    }

    function showTable(string $name) {
        foreach ($this->tables[$name] as $record) {
            echo '<div style="background-color:lightskyblue; margin:5px;">';
            foreach ($record as $key => $value) {
                echo '(' . $key . ' = ' . $value . ') ';
            }
            echo '</div>';
        }
    }
}
