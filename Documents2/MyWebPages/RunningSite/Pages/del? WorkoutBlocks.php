
<?php

session_start();

include_once '../../Common/PHP/all.php';

list($html, $head, $body) = makePage('Track Repetition Pace Calculator', ['home' => 'index.php']);



$parentStyle = ['style'=>'display:flex; background-color:green; height:50ch; width:100ch; align-items:flex-end;'];
$childStyle = ['style'=>'display:inline-block; background-color:red; height: 50%; width: 20%; margin:1ch;'];

$workoutDiv = $body->makeChild('div','',$parentStyle);
$workoutDiv->makeChild('div','',$childStyle);
$workoutDiv->makeChild('div','',$childStyle);


$html->echo();

