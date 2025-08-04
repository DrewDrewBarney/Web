<?php

session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../ClubPHP/clubAll.php';
include_once 'menu.php';

list($html, $head, $body) = makePage("Athletic Club Angerien", '');

$topBar = makeTopBar();
$topBar->addChildren([
    makePageTitle("Athletic Club Angerien"),
    makeMenu(MENU_CLUB_HOME, 'clubHome.php'),
    makePageTitle("Accueil")
]);

//$body->setAttribute('onload', javaFunctionSetImagePointer . '; setImagePointer();');

$body->setAttribute('onclick', javaFunctionToggleMusic . '; toggleMusic();');

$body->setAttribute('id', 'body');


$body->setAttribute('style', 'background-color: black;');

$body->addChild($topBar);

// image banner
$body->addChild(makeClubImages());

$body->addChild(makeClubBanner(""));

$body->makeChild('div', '', ['style' => 'height:48vh;']);

if (UserManagement::loggedIn()) {
    $html->makeChild('div', 'Logged in', ['class' => 'transientShowLoggedIn']);
    $body->makeChild('div', UserManagement::count() . ' registered users', ['class' => 'numberOfUsers']);
}

$body->addChild(makeFooter());

$audio = $body->makeChild('audio','',['id'=>'audio']);

$body->makechild('script', storeImagesScript());
$body->makechild('script', javaScriptSwitchImages);

$html->echo();


