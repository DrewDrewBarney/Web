<!DOCTYPE HTML>

<?php
session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
//include_once 'menu.php';
//UserManagement::protect();



list($html, $head, $body) = makePage('Marathon Plan', ['home' => 'index.php']);

$styleSrc = 'width:300px; height:100px; margin:20px; background-color:cyan;';
$styleDst = 'height: 50px; margin: 20px; background-color:gray;';
$html->makeChild('script', '', ['src' => 'DragDrop.js']);

$form = $body->makeChild('form', '', ['id' => 'form']);


$form->makeChild('input', '', ['id' => 'delta', 'name' => 'delta']);

// SPOOF A WORKOUT

$warmupDistance = 1.5;
$warmupSpeed = 8;
$cooldownDistance = 1.0;
$cooldownSpeed = 8;
$activeDistance = 0.4;
$activeSpeed = 12;
$recoveryDistance = 0.2;
$recoverySpeed = 6;

$warmupPhase = ['type' => 'warmup', 'distance' => $warmupDistance, 'speed' => $warmupSpeed];
$activePhase = ['type' => 'active', 'distance' => $activeDistance, 'speed' => $activeSpeed];
$recoveryPhase = ['type' => 'recovery', 'distance' => $recoveryDistance, 'speed' => $recoverySpeed];
$cooldownPhase = ['type' => 'cooldown', 'distance' => $cooldownDistance, 'speed' => $cooldownSpeed];
$repetitions = 5;

if ($repetitions > 1) {
    $reps = ['type' => 'repetition', 'reps' => $repetitions, 'workout' => [$activePhase, $recoveryPhase]];
    $workout = [$warmupPhase, $reps, $cooldownPhase];
} else {
    $workout = [$warmupPhase, $activePhase, $recoveryPhase, $cooldownPhase];
};

// BUILD THE WORKOUT DISPLAY

$body->addChild(buildWorkoutDisplay($workout));

$html->echo();

print_r($_GET);

function buildRepetitionStep(Array $step): Tag {
    $reps = $step['reps'];
    $workout = $step['workout'];
    $repetitionStyle = 'min-height:50px; margin:20px; background-color:yellow';
    $result = Tag::make('div', '', ['style' => $repetitionStyle, 'draggable' => 'true', 'ondragstart' => 'dragstartHandler(event)']);
    $result->makeChild('input', ' reps', ['type' => 'number', 'value' => $reps, 'min' => '2', 'max' => '100']);
    $result->addChild(buildWorkoutDisplay($workout));
    return $result;
}

function buildSimpleStep(Array $step): Tag {
    $stepStyle = 'width:300px; height:100px; margin:20px; background-color:cyan;';
    $result = Tag::make('div', '', ['draggable' => 'true', 'ondragstart' => 'dragstartHandler(event)']);
    $result->setAttribute('style', $stepStyle);
    return $result;
}

function buildStep(Array $step): Tag {
    if (isset($step['workout'])) {
        return buildRepetitionStep($step);
    } else {
        return buildSimpleStep($step);
    }
}

function buildLandingStrip(Array $step): Tag {
    $landerStyle = 'background-color:black; height:10px;';
    return Tag::make('div', '', ['style' => $landerStyle, 'ondragover' => 'dragoverHandler(event)', 'ondrop' => 'dropHandler(event)']);
}

function buildWorkoutDisplay(Array $workout): Tag {

    $wrapperStyle = 'background-color:lightgray;';
    $result = Tag::make('div', '', ['style' => $wrapperStyle]);
    foreach ($workout as $step) {

        $result->addChild(buildLandingStrip($step));
        $result->addChild(buildStep($step));
    }
    
    $result->createIndices();
    return $result;
}
