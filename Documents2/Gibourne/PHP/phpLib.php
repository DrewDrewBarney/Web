<?php

function head($title) { ?>
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="CSS/DrewsStyle.css?id=<?php echo rand(); ?>">
    </head>
<?php } ?>

<?php

function banner($title) { ?>
    <div>
        <img src ="Images/flash.png" style="float:left;"  class="dazzle">
        <header>
            <?php echo $title; ?>
        </header>
    </div>

<?php } ?>

<?php

function footer() { ?>
    <footer>
        <img class="imageFloatRight" src="Images/CMLogo.png">
        <p style='color:#d7bf96 ;'>Web Author: Dr Drew Shardlow</p>
        <p>
            17160 Gibourne<br>
        <p>
            <a  href="mailto:shardlow.a@gmail.com">shardlow.a@gmail.com</a>           
        </p>

        <?php injectKeys(); ?>

    </footer>
<?php } ?>

<?php

function injectKeys() {
    $keys = ['running', 'cycling', 'swimming', 'performance', 'race', 'racing'];
    foreach ($keys as $value) {
        echo "<div style = 'visibility:collapse; ' >" . $value . '</div>';
    }
}

function frac($over, $under) {
    $result = "\n<table style='vertical-align: middle; text-align: center; margin-left:0.5ch; margin-right: 0.5ch;  display: inline-table; color: inherit; font-weight: inherit; font-size:inherit;'>\n";

    $result .= "<tr>\n";
    $result .= "<td style = 'padding-bottom:3px;'>\n";
    $result .= equation($over);
    $result .= "</td>\n";
    $result .= "</tr>\n";

    $result .= "<tr>\n<td style='margin:0px; padding:0px; height: 1px; background-color: black;'></td></tr>";

    $result .= "<tr>\n";
    $result .= "<td  style = 'padding-top:3px;'>\n";
    $result .= equation($under);
    $result .= "</td>\n";
    $result .= "</tr>\n";

    $result .= "</table>\n";
    return $result;
}

function equation($items) {
    $result = "\n<table style='vertical-align: middle; text-align: center; margin-left:1ch; margin-right: 1ch; display: inline-table; color: inherit; font-weight: inherit; font-size:inherit;'>\n";
    $result .= "<tr>\n";
    if (is_array($items)) {
        foreach ($items as $item) {

            $result .= "<td style='padding-left: 0.5ch; padding-right:0.5ch;'>" . $item . "</td>\n";
        }
    } else {
        $result .= "<td style='padding-left: 0.5ch; padding-right:0.5ch;'>" . $items . "</td>\n";
    }
    $result .= "</tr>";
    $result .= "</table>";
    return $result;
}

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
