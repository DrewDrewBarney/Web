<?php

include_once 'birthdayTools.php';
include_once 'makeButterflyCSS.php';
include_once '../../Common/PHP/dom.php';

const javaFunctionToggleMusic = 
    "
        function toggleMusic(){
            const player = document.getElementById('audio');
            if (player.paused){
                player.src = 'breeze.mp3';
                player.play();    
            } else {
                player.pause();
            }
        }
    ";


makeButterflyCSS();
$html = Tag::make('html');
$html->addChild(makeHead('Happy Birthday'));
$body = $html->makeChild('body');
$body->setAttribute('onclick', javaFunctionToggleMusic . '; toggleMusic();');


for ($i = 0; $i < 4; $i++) {
    $body->makeChild('img', '', ['src' => 'but1.png', 'class' => "butterfly" . $i]);   
    $body->makeChild('img', '', ['src' => 'but2.png', 'class' => "butterfly".$i+4]);
    $body->makeChild('img', '', ['src' => 'but3.png', 'class' => "butterfly".$i+8]);
}


/*
$body->makeChild('h1', 'Happy Birthday Emma');
$body->makeChild('h1', 'love Drew');
$body->makeChild('h1', 'xxxxxx');
$body->makeChild('h1', 'xxxx');
 * 
 */

$audio = $body->makeChild('audio','',['id'=>'audio']);

$html->echo();

