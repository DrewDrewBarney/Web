<?php

include_once '../../Common/PHP/all.php';

////////////////////////////////////////////////////////////////////////////////////////////////
/// MAKE THE MARATHON PLAN PAGE 
////////////////////////////////////////////////////////////////////////////////////////////////


list($html, $head, $body) = makePage('M E R R Y  C H R I S T M A S', ['home' => 'index.php']);

$html->setAttributes(['style'=>'background-color:black;']);
$body->setAttributes(['style'=>'background-color:black;']);

$body->makeChild('div', 'MERRY CHRISTMAS!',['style'=>'font-size:3em; color:goldenrod; text-align:center; padding:1em;']);

$imageDiv = $body->makeChild('div','',['style'=>'text-align:center;']);

$imageDiv->makeChild('img','', ['src'=>'../Images/Christmas.png', 'style'=>'width:80vw;']);

foreach (range(0, 500, 1) as $index) {
    $x = rand(-0,100);
    $y1 = rand(-100,0);
    $y2 = 100 + $y1;
    $y3 = 100 + $y2;
    $d = rand(1,40);
    $blur = rand(0,3);
    $style1 =  "width: " . $d . "px; position: absolute; left:".$x."vw; top:".$y1."vh; filter:blur(".$blur."px);";
    $style2 = "width: " . $d . "px; position: absolute; left:".$x."vw; top:".$y2."vh; filter:blur(".$blur."px);";
    $style3 = "width: " . $d . "px; position: absolute; left:".$x."vw; top:".$y3."vh; filter:blur(".$blur."px);";
    $imageDiv->makeChild('img', '', ['src' => '../Images/snowflake.svg', 'style' => $style1, 'class'=>'falling']);
    $imageDiv->makeChild('img', '', ['src' => '../Images/snowflake.svg', 'style' => $style2, 'class'=>'falling']);
    $imageDiv->makeChild('img', '', ['src' => '../Images/snowflake.svg', 'style' => $style3, 'class'=>'falling']);
}


$trainingArticle = $body->makeChild('div','',['style'=>'margin: 10px;']);
$trainingArticle->makeChild('p',
        'Merry Christmas Emma, Tom, Sam, Euan, Isla, Riley, Harry and Oscar?<br><br>'
        . 'Lots of love from Dad, Emma and all the criters<br><br>'
        . 'X X X X X X'
        
        ,['style'=>'font-family: cursive; font-size:3em; color:goldenrod; text-align:center;']
        
        
        
        );

$html->echo();
