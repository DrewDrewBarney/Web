<?php

/*
  function safeRead(array $a, string $key, $default = null){
  if (in_array($key, $a)){
  return $a[$key];
  } else {
  return $default;
  }
  }
 * */

function decimalMinutesToMinsSecsString(float $decimalMinutes) {
    $wholeSecs = intval(60 * $decimalMinutes);
    $wholeMins = intdiv($wholeSecs, 60);
    $wholeHours = intdiv($wholeSecs, 3600);

    $hours = $wholeHours;
    $mins = $wholeMins % 60;
    $secs = $wholeSecs % 60;

    //return $mins . ":" . $secs;
    if ($hours) {
        return sprintf("%01d:%02d:%02d", $hours, $mins, $secs);
    } else {
        return sprintf("%01d:%02d", $mins, $secs);
    }
}

function decimalMinutesFromString($minsSecs) {
    $result = 0;
    preg_match_all('!\d+\.*\d*!', $minsSecs, $matches);
    //print_r($values);
    if (sizeof($matches) == 1) {
        $values = $matches[0];
        if (sizeof($values) == 1) {
            $result = intval($values[0]);
        } else if (sizeof($values) == 2) {
            $result = intval($values[0]) + intval($values[1]) / 60;
        }
    }
    return $result;
}


/*
function showGetPost() {
    $result = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $result = "POST\n";
        foreach($_POST as $key=>$value){
            $result .= $key . '=>' . $value . "\n";
        }
    } else {
        $result = "GET\n";
        foreach($_GET as $key=>$value){
            $result .= $key . '=>' . $value . "\n";
        }
    }
    echo '<pre>' . $result . '</pre>';
}
*/

function uniquePageID(){
    static $id = 1000;
    return ($id++);
}