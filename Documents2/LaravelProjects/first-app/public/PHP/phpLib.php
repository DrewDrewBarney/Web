<?php
include_once 'all.php';



class SimpleTable {

    var $mRowsCols;
    var $mCaption;
    var $mTableStyle = ['margin' => '3ch', 'margin-left' => 'auto', 'margin-right' => 'auto', 'width' => '50ch', 'border-collapse' => 'collapse', 'border-width' => '0px'];
    var $mTitleRowStyle = ['background-color' => '#DDC', 'padding' => '5px 10px', 'text-align' => 'center', 'font-weight' => 'bold', 'border-style' => 'solid', 'border-width' => '3px', 'border-color' => '#EED'];
    var $mTitleColStyle = ['background-color' => '#EED', 'padding' => '5px 10px', 'text-align' => 'center', 'font-weight' => 'bold', 'border-style' => 'solid', 'border-width' => '3px', 'border-color' => '#EED'];
    var $mItemStyle = ['background-color' => 'white', 'padding' => '5px 10px', 'text-align' => 'center', 'border-style' => 'solid', 'border-width' => '3px', 'border-color' => '#EED'];

    function __construct($rowsCols) {
        $this->mRowsCols = $rowsCols;
    }

    function addCaption($caption) {
        $this->mCaption = $caption;
    }

    function setTableStyle($key, $style) {
        $this->mTableStyle[$key] = $style;
    }

    function toStyleString($styles) {
        $result = '';
        foreach ($styles as $key => $value) {
            $result .= $key . ':' . $value . ';';
        }
        return $result;
    }

    function toString() {
        $result = "<table style = " . $this->toStyleString($this->mTableStyle) . ">";

        if ($this->mCaption)
            $result .= "<caption>$this->mCaption</caption>";

        $rowIndex = 0;
        foreach ($this->mRowsCols as $row) {
            $rowStyle = $rowIndex == 0 ? $this->mTitleRowStyle : $this->mItemStyle;
            $result .= "<tr>";

            $colIndex = 0;
            foreach ($row as $item) {
                if ($rowIndex === 0) {
                    $style = $this->mTitleRowStyle;
                } else if ($colIndex === 0) {
                    $style = $this->mTitleColStyle;
                } else {
                    $style = $this->mItemStyle;
                }
                $result .= "<td style = '" . $this->toStyleString($style) . "'>" . $item . '</td>';
                $colIndex++;
            }
            $result .= '</tr>';

            $rowIndex++;
        }

        $result .= '</table>';
        return $result;
    }

}


