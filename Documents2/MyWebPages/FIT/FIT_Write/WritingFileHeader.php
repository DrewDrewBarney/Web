<?php

include_once 'WritingTools.php';


class WritingFileHeader {

    const HEADER_SIZE = 14;
    const PROTOCOL_VERSION = 16;
    const PROFILE_VERSION_LSB = 0;
    const PROFILE_VERSION_MSB = 1;
    const DATA_TYPE_BYTES = [46, 70, 73, 84];
    const CRC_LSB = 0;
    const CRC_MSB = 0;

    var array $headerBytes = [];

    function __construct(int $dataSize) {

        $this->headerBytes[] = self::HEADER_SIZE;
        $this->headerBytes[] = self::PROTOCOL_VERSION;
        $this->headerBytes[] = self::PROFILE_VERSION_LSB;
        $this->headerBytes[] = self::PROFILE_VERSION_MSB;
        
        foreach(WritingTools::intToBytes($dataSize, 4) as $byte){
            $this->headerBytes[] = $byte;            
        }
        
        foreach (self::DATA_TYPE_BYTES as $byte) {
            $this->headerBytes[] = $byte;
        }
        $this->headerBytes[] = self::CRC_LSB;
        $this->headerBytes[] = self::CRC_MSB;
    }

    function toBytes(): array {

        return $this->headerBytes;
    }
}
