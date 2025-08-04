<?php

class RecordHeader {

    // RECORD HEADER BYTE BIT MASKS
    const RECORD_HEADER_TYPE_BIT_POS = 7; // normal or compressed timestamp header
    const RECORD_HEADER_MESSAGE_TYPE_BIT_POS = 6; // definition or data header
    const RECORD_HEADER_MESSAGE_TYPE_SPECIFIC_BIT_POS = 5; // if data, whether developer data fields are present
    const RECORD_HEADER_RESERVED_BIT_POS = 4;
    const RECORD_HEADER_LOCAL_MESSAGE_TYPE_BIT_MASK = 0b1111; // mask to get local message type from standard data record
    const RECORD_HEADER_LOCAL_MESSAGE_TYPE_FOR_COMPRESSED_TIMESTAMP_BIT_MASK = 0b1100000; // mask to get local message type from compressed time stamp data record
    const RECORD_HEADER_LOCAL_MESSAGE_TYPE_FOR_COMPRESSED_TIMESTAMP_RIGHT_SHIFT = 5;
    //
    //
    // HEADER BYTE VALUES
    const RECORD_HEADER_TYPE_NORMAL = 0;
    const RECORD_HEADER_TYPE_TIME = 1;
    const RECORD_HEADER_TYPE_STRINGS = [
        self::RECORD_HEADER_TYPE_NORMAL => 'Normal Header',
        self::RECORD_HEADER_TYPE_TIME => 'Time Stamp Header'];
    const RECORD_HEADER_MESSAGE_TYPE_DATA = 0;
    const RECORD_HEADER_MESSAGE_TYPE_DEFINITION = 1;
    const RECORD_HEADER_MESSAGE_TYPE_STRINGS = [
        self::RECORD_HEADER_MESSAGE_TYPE_DATA => 'Data Message',
        self::RECORD_HEADER_MESSAGE_TYPE_DEFINITION => 'Definition Message'];
    //
    //
    // HUMAN READABLE
    const RECORD_HEADER_MESSAGE_TYPE_SPECIFIC_STRINGS = [
        0 => 'No Developer Data',
        1 => '<b>Developer Data Present</b>'];
    const RECORD_HEADER_RESERVED_STRINGS = [0 => 'Reserved'];

    // RECORD HEADER BIT VALUES
    var int $recordHeaderByte = 0;
    var int $recordHeaderType = 0;
    var int $messageType = 0;
    var int $messageTypeSpecific = 0;
    var int $reserved = 0;
    var int $localMessageType = 0;

    // FOR DISPLAY ONLY
    const COLORS = [
        'mistyrose', 
        'peachpuff', 
        'palegreen', 
        'aquamarine', 
        'paleturquoise', 
        'coral', 
        'khaki', 
        'lightskyblue', 
        'plum', 
        'violet', 
        'hotpink', 
        'darkseagreen',
        'sienns',
        'darkgoldenrod',
        'deepskyblue',
        'magenta',
        'crimson',
        'yellowgreen',
        'thistle',
        'darkcyan',
        
        ];

    function __construct(int $recordHeader) {
        $this->recordHeaderByte = $recordHeader;
        $this->recordHeaderType = ($recordHeader & (1 << self::RECORD_HEADER_TYPE_BIT_POS)) >> self::RECORD_HEADER_TYPE_BIT_POS;
        if ($this->recordHeaderType == self::RECORD_HEADER_TYPE_NORMAL) {
            $this->messageType = ($recordHeader & (1 << self::RECORD_HEADER_MESSAGE_TYPE_BIT_POS)) >> self::RECORD_HEADER_MESSAGE_TYPE_BIT_POS;
            $this->messageTypeSpecific = ($recordHeader & (1 << self::RECORD_HEADER_MESSAGE_TYPE_SPECIFIC_BIT_POS)) >> self::RECORD_HEADER_MESSAGE_TYPE_SPECIFIC_BIT_POS;
            $this->reserved = ($recordHeader & (1 << self::RECORD_HEADER_RESERVED_BIT_POS)) >> self::RECORD_HEADER_RESERVED_BIT_POS;
            $this->localMessageType = $recordHeader & self::RECORD_HEADER_LOCAL_MESSAGE_TYPE_BIT_MASK;
        } else if ($this->recordHeaderType == self::RECORD_HEADER_TYPE_TIME) {
            $this->messageType = self::RECORD_HEADER_MESSAGE_TYPE_DATA; // has to be data in FIT definition
            $this->localMessageType = ($recordHeader & self::RECORD_HEADER_LOCAL_MESSAGE_TYPE_FOR_COMPRESSED_TIMESTAMP_BIT_MASK) >> self::RECORD_HEADER_LOCAL_MESSAGE_TYPE_FOR_COMPRESSED_TIMESTAMP_RIGHT_SHIFT;
        }
    }

    function toString(string $nl = ''): string {
        $result = '<b>RECORD HEADER __ ' . FitTools::binaryString($this->recordHeaderByte, 8) . '</b>' . $nl;
        $result .= '__ ' . self::RECORD_HEADER_TYPE_STRINGS[$this->recordHeaderType] . $nl;
        $result .= '__ <b>' . self::RECORD_HEADER_MESSAGE_TYPE_STRINGS[$this->messageType] . '</b>' . $nl;
        $result .= '__ ' . self::RECORD_HEADER_MESSAGE_TYPE_SPECIFIC_STRINGS[$this->messageTypeSpecific] . $nl;
        $result .= '__ Local message type __ <b>' . $this->localMessageType . '</b>';
        return $result;
    }

    function backgroundColor($globalMessageNumber = 0): string {
        $color = self::COLORS[$globalMessageNumber % sizeof(self::COLORS)];
        return $color;
    }

    function show(string $backgroundColor = ''): void {
        $style = 'background-color:' . $backgroundColor . '; margin:5px';
        FITdebug::echo("<div style='$style'>" . $this->toString("<br>\n") . '</div>');
    }
}
