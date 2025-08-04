<?php

include_once '../../Common/PHP/all.php';

////////////////////////////////////////////////////////////////////////////////////////////////
/// MAKE THE MARATHON PLAN PAGE 
////////////////////////////////////////////////////////////////////////////////////////////////


list($html, $head, $body) = makePage('H A P P Y  B I R T H D A Y', ['home' => 'index.php']);

$html->setAttributes(['style'=>'background-color:yellow;']);

$body->makeChild('script',
        "document.body.addEventListener('click', function() {" .
          
            "document.getElementById('vid').play();" .
        "}, true);"
);

$div = $body->makeChild('div', '', ['style' => 'text-align:center; background-color:yellow;']);
$div->makeChild('p', 'Happy Birthday Isla!', ['style' => 'font-family:brush script MT; font-size:4em;', 'class'=>'wobble']);
$div->makeChild('video', 'browser does not support video', ['src' => '../Movies/Muppets.mp4', 'id' => 'vid', 'width'=>'80%']);

//$div->makeChild('img', '', ['src' => '../Images/birthday.png', 'class'=>'wobble']);
$div->makeChild('img', '', ['src' => '../Images/birthdayCake.png', 'style' => 'width: 30%; position:absolute; bottom>10%; left:34%; z-index:0;', 'class'=>'wobble']);
//$div = $body->makeChild('div','',['style'=>'background-color:yellow; text-align:center; z-index:2;']);
$div->makeChild('p', 'Have a lovely birthday. Love from Grandpa, Emma and all the criters here in Gibourne',['style'=>'position:absolute; bottom: -20%; left:5%; font-family:brush script MT; font-size:4em; background-color:rgba(0,0,0,0); z-index:10;']);
$div->makeChild('p', 'X X X X',['style'=>'position:absolute; bottom: -30%; left:40%; font-family:brush script MT; font-size:4em; background-color:rgba(0,0,0,0); z-index:10;']);

$html->echo();
