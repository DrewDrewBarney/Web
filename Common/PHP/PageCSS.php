<?php

/*
 * USED TO GENERATE TEDIOUSLY REPETITIVE CSS CLASS DEFINITIONS FOR POSITIONING
 */

class PageCSS {

    static protected function generateSpacingsCSS(): string {
        $units = ["ch", "em"];
        $sizes = [0, 0.5, 1, 1.5, 2, 3, 4, 5];
        $css = "";

        foreach ($sizes as $size) {
            foreach ($units as $unit) {
                $suffix = strpos($size, '.') !== false ? str_replace('.', '_', $size) . $unit : $size . $unit;
                $css .= ".margin-bottom-$suffix { margin-bottom: {$size}{$unit}; }\n";
                $css .= ".margin-top-$suffix { margin-top: {$size}{$unit}; }\n";
                $css .= ".margin-left-$suffix { margin-left: {$size}{$unit}; }\n";
                $css .= ".margin-right-$suffix { margin-right: {$size}{$unit}; }\n";
                $css .= ".margin-$suffix  { margin: {$size}{$unit}; }\n";
            }
        }
        return $css;
    }

    static protected function returnEmptiesCSS(): string {
        $result = "";
        foreach (range(1, 3) as $row) {
            foreach (range(1, 2) as $col) {
                $result .= ".empty_$row" . "_$col { grid-row: $row; grid-column: $col; }\n";
            }
        }
        return $result;
    }

  

    static protected function generatePositionsCSS(): string {
        $css = "";

        /* Universal center container (flex-based) */

        $css .= ".center-x {
                    display:flex; 
                    justify-content:center;
                }\n;
                .center-y {
                    display: flex;
                    align-items: center;
                }\n;
                    .center-xy {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }";

        /* No more weird inline-block spacing issues */
        $css .= ".inline {
                    display: inline - flex;
                    align-items: center;
                }";

        return $css;
    }
    
    static protected function errorCSS(): string{
        return ".error{color:red;}";
    }

    static function currentCssTag(): Tag {
        return Tag::make('style',
                        self::generateSpacingsCSS()
                        . self::generatePositionsCSS()
                        . self::returnEmptiesCSS()
                .self::errorCSS()
        );
    }
    
  
}
