<?php

include_once 'birthdayTools.php';
include_once 'makeStockCSS.php';
include_once '../../Common/PHP/dom.php';

const javaFunctionToggleMusic = 
    "
        function toggleMusic(){
            const player = document.getElementById('audio');
            if (player.paused){
                player.src = 'casualty.mp3';
                player.play();    
            } else {
                player.pause();
            }
        }
    ";


makeStockCSS();
$html = Tag::make('html');
$head = makeHead('Happy Birthday');
$head->makeChild("link", "", ["rel" => "stylesheet", "type" => "text/css", "href" => "stock.css"]);

$html->addChild($head);
$body = $html->makeChild('body', '', ['style'=>'background-image:url(bloodWagon.jpg);'], true);
$body->setAttribute('onclick', javaFunctionToggleMusic . '; toggleMusic();');


for ($i = 0; $i < 4; $i++) {
    $body->makeChild('img', '', ['src' => 'blood.png', 'class' => "butterfly" . $i]);   
    $body->makeChild('img', '', ['src' => 'bag.png', 'class' => "butterfly".$i+4]);
    $body->makeChild('img', '', ['src' => 'pills.png', 'class' => "butterfly".$i+8]);
}



$body->makeChild('h1', "Happy Father's Day");
$body->makeChild('h1', 'love from Emma & Drew');
$body->makeChild('h1', 'xxxxxx');


$audio = $body->makeChild('audio','',['id'=>'audio']);

$html->echo();

