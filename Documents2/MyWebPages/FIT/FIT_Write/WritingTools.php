<?php

class WritingTools {

    static function intToBytes(int $val, int $byteWidth, bool $bigEndian = false): array {
        $result = [];

        if ($bigEndian) {
            $mask = ((1 << 8) - 1) << (8 * $byteWidth);
            for ($i = 0; $i < $byteWidth; $i++) {
                $result[] = $val & $mask;
                $mask >>= 8;
            }
        } else {
            $mask = (1 << 8) - 1;
            for ($i = 0; $i < $byteWidth; $i++) {
                $result[] = ($val & $mask) >> (8 * $i);
                $mask <<= 8;
            }
        }

        return $result;
    }
    

    const crc_table = [
        0x0000, 0xCC01, 0xD801, 0x1400, 0xF001, 0x3C00, 0x2800, 0xE401,
        0xA001, 0x6C00, 0x7800, 0xB401, 0x5000, 0x9C01, 0x8801, 0x4400
    ];

    static function CRC(int $crc, int $byte) {

        // compute checksum of lower four bits of byte      
        $tmp = self::crc_table[$crc & 0xF];
        $crc = ($crc >> 4) & 0x0FFF;
        $crc = $crc ^ $tmp ^ self::crc_table[$byte & 0xF];

        // now compute checksum of upper four bits of byte
        $tmp = self::crc_table[$crc & 0xF];
        $crc = ($crc >> 4) & 0x0FFF;
        $crc = $crc ^ $tmp ^ self::crc_table[($byte >> 4) & 0xF];
        
        return $crc;
    }

    static function getCRC(array $bytes) {
        $crc = 0;
        foreach ($bytes as $byte) {
            $crc = self::CRC($crc, $byte);
        }
        return self::intToBytes($crc, 2);
    }
}

/*
    
    
    FIT_UINT16 FitCRC_Get16(FIT_UINT16 crc, FIT_UINT8 byte)
{
  
   FIT_UINT16 tmp;

   // compute checksum of lower four bits of byte
   tmp = crc_table[crc & 0xF];
   crc = (crc >> 4) & 0x0FFF;
   crc = crc ^ tmp ^ crc_table[byte & 0xF];

   // now compute checksum of upper four bits of byte
   tmp = crc_table[crc & 0xF];
   crc = (crc >> 4) & 0x0FFF;
   crc = crc ^ tmp ^ crc_table[(byte >> 4) & 0xF];

   return crc;
}
}
     * 
     */
