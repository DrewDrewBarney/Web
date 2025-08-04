<?php

class WritingDefinitionRecord {

    const HEADER_TYPE_NORMAL = 0;
    const HEADER_TYPE_COMPRESSED_TIMESTAMP = 1 << 7;
    const DATA_MESSAGE = 0;
    const DEFINITION_MESSAGE = 1 << 6;
    const NO_DEVELOPER_DATA = 0;
    const DEVELOPER_DATA = 1 << 5;

    // HEADER BYTE INFO IN BITS
    var int $headerType = self::HEADER_TYPE_NORMAL;
    var int $recordType = self::DEFINITION_MESSAGE;
    var int $developerData = self::DEVELOPER_DATA;
    var int $localMsgNo = 0;
    // HEADER BYTE
    var int $headerByte = 0;
    // DEFINITION RECORD DATA IN BYTES
    var array $fields = [];
    var int $reserved = 0;
    var int $architecture = 0;
    var int $globalMsgNo = 0;

    function __construct(array $attr = []) {
        $this->globalMsgNo = isset($attr['globalMsgNo']) ? $attr['globalMsgNo'] : 0;
        //
        $this->headerType = isset($attr['headerType']) ? $attr['headerType'] : self::HEADER_TYPE_NORMAL;
        $this->recordType = isset($attr['recordType']) ? $attr['recordType'] : self::DEFINITION_MESSAGE;
        $this->developerData = isset($attr['developerData']) ? $attr['developerData'] : self::NO_DEVELOPER_DATA;

        $this->localMsgNo = isset($attr['localMsgNo']) ? $attr['localMsgNo'] : 0;

        $this->headerByte = $this->headerType | $this->recordType | $this->developerData | $this->localMsgNo;
    }

    function addField(WritingDefinitionField $field) {
        $this->fields[] = $field;
    }

    function numberOfFields(): int {
        return sizeof($this->fields);
    }

    function toBytes(): array {
        $result = [$this->headerByte];
        $result[] = $this->reserved;
        $result[] = $this->architecture;
        $result = array_merge($result, WritingTools::intToBytes($this->globalMsgNo, 2));
        $result[] = $this->numberOfFields();
        foreach ($this->fields as $field) {
            $result = array_merge($result, $field->toBytes());
        }
        return $result;
    }

    function size(): int {
        return sizeof($this->toBytes());
    }
}
