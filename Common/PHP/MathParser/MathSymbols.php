<?php

class MathSymbols {

    private static function makeSVG(string $label, int $x, int $y, int $width, int $height): Tag {
        return Tag::make('svg', '', [
                    'xmlns' => 'http://www.w3.org/2000/svg',
                    'viewBox' => "$x $y $width $height",
                    'preserveAspectRatio' => 'none',
                    'role' => 'img',
                    'aria-label' => $label
        ]);
    }

    public static function sqrt(): string {
        $virtualWidth = 30;
        $virtualHeight = 100;
        $scaleX = $virtualWidth / 100;
        $scaleY = $virtualHeight / 100;

        $svg = self::makeSVG('Root Symbol', 0, 0, 30, 100);

        // Define two lines in 100x100 virtual canvas
        $lines = [
            [2.5, 60, 10, 60],
            [10, 60, 40, 95], // descending leg
            [40, 95, 100, 0], // angled tick
        ];

        // scaling factors (if virtualWidth != 100)

        foreach ($lines as [$x1, $y1, $x2, $y2]) {

            $x1s = $x1 * $scaleX;
            $y1s = $y1 * $scaleY;
            $x2s = $x2 * $scaleX;
            $y2s = $y2 * $scaleY;

            $svg->makeChild('line', '', [
                'x1' => $x1s,
                'y1' => $y1s,
                'x2' => $x2s,
                'y2' => $y2s,
                'stroke' => 'black',
                'stroke-width' => '2',
                'stroke-linecap' => 'round',
                'vector-effect' => 'non-scaling-stroke'
            ]);
        }
        return $svg->toString();
    }

    public static function int(): string {
        $virtualWidth = 30;
        $virtualHeight = 100;
        $scaleX = $virtualWidth / 100;
        $scaleY = $virtualHeight / 100;

        $svg = self::makeSVG('Integrate Symbol', 20, 0, 130, 300);

        $svg->makeChild('path', '', [
            'id' => 'path835',
            'd' => 'm 24.568452,259.29166 c 0,0 1.360373,22.67414 10.205358,21.92262 9.895107,-0.84074 10.515656,-12.50813 12.095238,-21.16666 7.8551,-43.05797 13.463779,-88.69825 17.502105,-115.58473 C 70.173126,105.83434 74.195201,72.020669 84.666666,31.75 86.416017,25.022428 86.787295,14.658324 97.517857,15.119048 108.0922,15.573064 108.85714,32.505951 108.85714,32.505951',
            'fill' => 'none',
            'stroke' => 'currentColor',
            'stroke-width' => '3',
            'stroke-linecap' => 'round',
            'stroke-linejoin' => 'round',
            'stroke-opacity' => '1',
            'stroke-miterlimit' => '4',
            'stroke-dasharray' => 'none',
            'paint-order' => 'markers stroke fill',
            // keep stroke width constant when scaling the symbol
            'vector-effect' => 'non-scaling-stroke',
        ]);

        return $svg->toString();
    }

    public static function lint(): string {
        $virtualWidth = 30;
        $virtualHeight = 100;
        $scaleX = $virtualWidth / 100;
        $scaleY = $virtualHeight / 100;

        $svg = self::makeSVG('Integrate Symbol', 20, 0, 130, 300);

        $svg->makeChild('path', '', [
            'id' => 'path835',
            'd' => 'm 24.568452,259.29166 c 0,0 1.360373,22.67414 10.205358,21.92262 9.895107,-0.84074 10.515656,-12.50813 12.095238,-21.16666 7.8551,-43.05797 13.463779,-88.69825 17.502105,-115.58473 C 70.173126,105.83434 74.195201,72.020669 84.666666,31.75 86.416017,25.022428 86.787295,14.658324 97.517857,15.119048 108.0922,15.573064 108.85714,32.505951 108.85714,32.505951',
            'fill' => 'none',
            'stroke' => 'currentColor',
            'stroke-width' => '3',
            'stroke-linecap' => 'round',
            'stroke-linejoin' => 'round',
            'stroke-opacity' => '1',
            'stroke-miterlimit' => '4',
            'stroke-dasharray' => 'none',
            'paint-order' => 'markers stroke fill',
            // keep stroke width constant when scaling the symbol
            'vector-effect' => 'non-scaling-stroke',
        ]);

        $svg->makeChild('path', '', [
            'style' => "fill:none;fill-rule:evenodd;stroke:#000000;stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:markers stroke fill",
            'id' => "path855",
            'sodipodi:type' => "arc",
            'sodipodi:cx' => "85.956535",
            'sodipodi:cy' => "131.89842",
            'sodipodi:rx' => "18.978048",
            'sodipodi:ry' => "21.633451",
            'sodipodi:start' => "1.5555107",
            'sodipodi:end' => "1.5500647",
            'sodipodi:arc-type' => "slice",
            'd' => "m 86.246615,153.52935 a 18.978048,21.633451 0 0 1 -19.265498,-21.27081 18.978048,21.633451 0 0 1 18.633668,-21.99006 18.978048,21.633451 0 0 1 19.316235,21.21093 18.978048,21.633451 0 0 1 -18.581067,22.04781 l -0.393418,-21.6288 z",
            'transform' => "matrix(0.98777721,0.15587235,-0.15539365,0.98785263,0,0)",
             // keep stroke width constant when scaling the symbol
            'vector-effect' => 'non-scaling-stroke',
        ]);

        return $svg->toString();
    }
}
