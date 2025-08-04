<?php

class FitTools {

    static function combineBytes(bool $bigEndian, array $bytes): int {
        $bytes = $bigEndian ? array_reverse($bytes) : $bytes;
        $result = 0;
        $base = 1;
        foreach ($bytes as $byte) {
            $result += $base * $byte;
            $base <<= 8;
        }
        return $result;
    }

    static function subArray(array $bytes, int $start, $len) {
        $result = [];
        foreach (range($start, $start + $len - 1) as $i) {
            if (isset($bytes[$i])) {
                $result[] = $bytes[$i];
            }
        }
        return $result;
    }

    static function binaryString(int $in, int $length) {
        $result = '';
        $bitmask = 1 << $length - 1;
        while ($bitmask > 0) {
            $result .= $in & $bitmask ? '1' : 0;
            $bitmask >>= 1;
        }
        return $result;
    }
}
