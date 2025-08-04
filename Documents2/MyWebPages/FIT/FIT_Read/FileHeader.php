<?php

include_once 'FitTools.php';

class FileHeader{
    
    var int $headerSize = 0;
    var int $protocolVersion = 0;
    var int $profileVersion = 0;
    var int $dataSize = 0;
    var string $dataType = '';
    var int $CRC = 0;
    
    
    function __construct(array $headerBytes) {
        
        $this->headerSize = sizeof($headerBytes);
        $this->protocolVersion = $headerBytes[1];
        $this->profileVersion = FitTools::combineBytes(false, FitTools::subArray($headerBytes, 2, 2));
        $this->dataSize = FitTools::combineBytes(false, FitTools::subArray($headerBytes, 4, 4));
        $this->dataType = implode(array_map("chr", FitTools::subArray($headerBytes, 8, 4)));
        $this->CRC = FitTools::combineBytes(false, FitTools::subArray($headerBytes, 12, 2));
        
    }
    
    function toString($nl = ''):string{
        $result = $nl;
        $result .= '<b>FILE HEADER</b>' .  $nl;
        
        $result .= 'File Header Size> ' . $this->headerSize . $nl;
        $result .= 'Protocol Version> ' . $this->protocolVersion . $nl;
        $result .= 'Profile Version> ' . $this->profileVersion . $nl;
        $result .= 'Data Size> ' . $this->dataSize . $nl;
        $result .= 'Data Type> ' . $this->dataType . $nl;
        $result .= 'CRC> ' . $this->CRC . $nl;
        
        return $result;
    }
    
    function show():void{
        FITdebug::echo($this->toString("<br>\n"));
    }
    
    
}