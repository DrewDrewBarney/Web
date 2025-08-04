<?php

session_start();

include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

list($html, $head, $body) = makePage("Drew's Resources for Athletes");

$current_file_name = basename($_SERVER['PHP_SELF']);

$topBar = makeTopBar();
$topBar->addChildren([
    makeMenu(MENU_HOME, 'basis.php'),
    makePageTitle('Underlying Concepts'),
    makeMenu(MENU_BASIS),
]);

$body->addChildren([
    $topBar,
    makeImage('../Images/basis.png'),
    makeFooter()
]);

$html->echo();

