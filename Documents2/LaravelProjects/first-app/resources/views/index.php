<?php
include_once 'PHP/all.php';


//mail('shardlow.a@gmail.com', 'index.php accessed', 'Index.php accessed');

list($html, $head, $body) = makePage("Drew's Resources for Athletes");


$body->makeChild('img', '', [
    'src' => 'Images/Cardiff10k.jpeg',
    'alt' => 'run',
    'style' => 'width:30%; float: right; padding: 1%;'
]);

$body->addChild(makeVerticalNavMenu([
    ['The Physiology of Training', 'physiology.php', ''],
    ['A Comparison of Training Intensity Measures', 'metrics.php', ''],
    ['Training Load & Performance', 'trainingLoad.php', ''],
    ['Track Interval Pace Calculator', 'intervalPaces.php', ''],
    ['Race Predictor', 'racePredictor.php', ''],
    ["Drew's Apps", 'myApps.php', ''],
    ['TCX File Tools', 'dataProcessingHome.php'],
    ['About Me', 'aboutMe.php', '']
]));

$body->addChild(makeFooter());

$html->echo();


