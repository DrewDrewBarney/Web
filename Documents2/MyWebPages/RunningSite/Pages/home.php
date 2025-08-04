<?php

session_start();

include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once '../../FIT/FIT_all.php';
include_once 'menu.php';

list($html, $head, $body) = makePage("Drew's Resource for Runners", '');

$topBar = makeTopBar();
$topBar->addChildren([
    makeMenu(MENU_HOME, 'home.php'),
    makePageTitle("A Training Resource for Runners")
]);

// TOP
$body->addChildren([
    $topBar,
    makeImage('../Images/home.png'),
]);

if (UserManagement::loggedIn()) {
    $html->makeChild('div', 'Logged in', ['class' => 'transientShowLoggedIn']);
    $body->makeChild('div', UserManagement::count() . ' registered users', ['class' => 'numberOfUsers']);
}


// AND TAIL
$body->addChild(makeFooter());

// RENDER
$html->echo();

