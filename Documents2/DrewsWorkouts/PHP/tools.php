<?php

/*
 *      Low level convenience functions
 */

function scream($msg) {
    echo "<h3>";
    print_r($msg);
    echo "</h3>\n";
}


// $index is assumed to be in range
function swapTypes(array &$array, int $index, int $delta, $classNames) {
    $newIndex = $index + $delta;
    while ($newIndex >= 0 && $newIndex < count($array)) {
        $className = get_class($array[$newIndex]);
        $allowSwap = array_search($className, $classNames);
        if ($allowSwap !== false) {
            //echo get_class($array[$index]);
            $temp = $array[$newIndex];
            $array[$newIndex] = $array[$index];
            $array[$index] = $temp;
            break;
        }
        $newIndex += $delta;
    }
    
    
}



