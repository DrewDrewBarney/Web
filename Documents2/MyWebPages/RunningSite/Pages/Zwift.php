<?php
session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

list($html, $head, $body) = makePage('Zwift Running Workouts');


$topBar = makeTopBar();
$topBar->addChildren([
    makeMenu(MENU_HOME, 'tools.php'),
    makePageTitle('Tools'),
    UserManagement::loggedIn() ? makeMenu(MENU_TOOLS_LOGGED_IN, 'Zwift.php') : makeMenu(MENU_TOOLS_LOGGED_OUT),
    makePageTitle('Zwift Running Workout Generator')
]);
$body->addChild($topBar);



include 'ZwiftBase.php';



$html->echo();
