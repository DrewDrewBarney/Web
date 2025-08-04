<?php

function makeStockCSS() {

    $result = '';
    $butterflyNumber = 12;
    $steps = 66;
    srand();

    for ($i = 0; $i < $butterflyNumber; $i++) {
        $name = "butterfly$i";
        $y1 = rand(100,150);
        $x1 = rand(40,80);
        $y2 = rand(0,-20);
        $x2 = rand(0,100);
        $dur = rand(10, 12) . 's';
        $result .= "\n@keyframes $name{";
        $dx = ($x2 - $x1) / $steps;
        $dy = ($y2 - $y1) / $steps;
        $angle = atan2($dy, $dx);
        $deg = 90 + 180 / pi() * $angle;

        $x = $x1;
        $y = $y1;
        for ($step = 0; $step < $steps; $step++) {
            $pc = 100 * $step / ($steps - 1);
            $x += $dx;
            $y += $dy;
            $flapScale = $step % 2 ? 1 : 1;
            $sizeScale = $steps / ($steps + 2 * $step);
            $orientation = $deg + rand(-10, 10);
            $scale = $flapScale * $sizeScale;
            $result .= "\n" . round($pc) . "%{transform: translate(" . round($x) . "vw, " . round($y) . "vh) rotate(" . $orientation . "deg) scale($scale,$sizeScale);}";
        }
        $result .= "\n}";

        $result .= "\n.butterfly$i {
        position: fixed;
        bottom: 0;
        left: 0;
        animation-name: butterfly$i;
        animation-duration: $dur ;
        animation-iteration-count: infinite;
        }";
    }



    file_put_contents('stock.css', $result);
}
