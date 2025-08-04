<?php

session_start();

include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

list($html, $head, $body) = makePage("Drew's Resources for Athletes", ['one', 'two'], 'CSS/DrewsStyle.css');


$topBar = makeTopBar();
$topBar->addChildren([
    makeMenu(MENU_HOME,'tools.php'),
    makePageTitle('Tools'),
    UserManagement::loggedIn() ? makeMenu(MENU_TOOLS_LOGGED_IN) : makeMenu(MENU_TOOLS_LOGGED_OUT),
]);

$body->addChildren([
    $topBar,
    makeImage('../Images/tools.png'),
    makeFooter()
]);

$html->echo();

