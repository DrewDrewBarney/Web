<?php

class DataRecord {

    var string $globalMessageName = '';
    var array $values;

    function __construct(string $globalMessageName, array $values) {
        $this->globalMessageName = $globalMessageName;
        $this->values = $values;
    }

    function show(string $backgroundColor = '') {
        $style = 'background-color:'.$backgroundColor.'; margin:5px;';
        FITdebug::echo("<div style='$style'>");
        FITdebug::echo('<b>'. $this->globalMessageName . '</b><br>');
        foreach ($this->values as $key => $value) {
            $value = is_array($value) ? '[' . $value[0] . '-' . $value[1] . ']' : $value;
            FITdebug::echo('(' . $key . ' = ' . $value . ') ');
        }
        echo "</div>";
    }
}
