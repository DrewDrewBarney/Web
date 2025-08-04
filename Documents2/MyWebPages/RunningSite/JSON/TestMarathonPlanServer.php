<?php

include_once '../../Common/PHP/all.php';

list($html, $head, $body) = makePage('Test Marathon Plan');

$body->addChild(makePageTitle('Test Marathon Plan'));


$form = $body->makeChild('form','',['method'=>'post', 'action'=>'MarathonPlanServer.php']);

$email = $form->makeChild('input',' email', ['type'=>'email', 'name'=>'email', 'id'=>'email']);
$password = $form->makeChild('input',' password', ['type'=>'password', 'name'=>'password', 'id'=>'password']);

$button = $form->makeChild('button', 'post');



$html->echo();

