<?php

session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';


list ($html, $header,$body)= makePage('Scratch');
$form = $body->makeChild('form');

$select = makeSelect(['one' => 1, 'two' => 2], 2,'select_it');
$form->addChild($select);
$form->makeChild('button','post',['name'=>'button', 'value'=>'post']);

$html->echo();

echo $select->value() . '<br>';

print_r($_GET);