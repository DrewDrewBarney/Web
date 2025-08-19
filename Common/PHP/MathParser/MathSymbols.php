<?php

class MathSymbols {

    public static function root(): string {
        $virtualWidth = 30;
        $virtualHeight = 100;

        // Define two lines in 100x100 virtual canvas
        $lines = [
            [10, 60, 40, 90], // descending leg
            [40, 90, 100, -5], // angled tick
        ];

        // scaling factors (if virtualWidth != 100)
        $scaleX = $virtualWidth / 100;
        $scaleY = $virtualHeight / 100;

        $lineMarkup = '';
        foreach ($lines as [$x1, $y1, $x2, $y2]) {
            $x1s = $x1 * $scaleX;
            $y1s = $y1 * $scaleY;
            $x2s = $x2 * $scaleX;
            $y2s = $y2 * $scaleY;
            $lineMarkup .= "<line x1=\"$x1s\" y1=\"$y1s\" x2=\"$x2s\" y2=\"$y2s\" stroke=\"black\" stroke-width=\"2\" stroke-linecap=\"round\" vector-effect=\"non-scaling-stroke\" />\n";
        }

        return <<<SVG
            <svg 
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 $virtualWidth $virtualHeight"
                
                preserveAspectRatio="none"
                role="img" aria-label="Root symbol"
            >
                $lineMarkup
            </svg>
SVG;
    }
}
