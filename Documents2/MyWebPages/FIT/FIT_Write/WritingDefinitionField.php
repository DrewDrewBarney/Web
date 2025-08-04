<?php

class WritingDefinitionField{
    
    const ENUM = 0;
    const SINT8 = 1;
    const UINT8 = 2;
    const SINT16 = 3;
    const UINT16 = 4;
    const SINT32 = 5;
    const UINT32 = 6;
    const STRING = 7;
    const FLOAT32 = 8;
    const FLOAT64 = 9;
    
    const UINT8Z = 10;
    const UINT16Z = 11;
    const UINT32Z = 12;
    const BYTE = 13;
    const SINT64 = 14;
    const UINT64 = 15;
    const UINT64Z = 16;
    
    var int $globalFieldNumber;
    var int $size;
    var int $baseType;
    
    
    function __construct(int $globalFieldNumber, int $size, int $baseType) {
        $this->globalFieldNumber = $globalFieldNumber;
        $this->size = $size;
        $this->baseType = $baseType;
    }
    
    
    function toBytes():array{
        return [$this->globalFieldNumber, $this->size, $this->baseType];
    }
    
    
}