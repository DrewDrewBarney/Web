<?php

session_start();

include_once '../../Common/PHP/roots.php';
include_once '../ClubPHP/clubAll.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

list($html, $head, $body) = makePage("Drew's Resource for Runners", '');

$topBar = makeTopBar();
$topBar->addChildren([
        makePageTitle("Athletic Club Angerien"),

    makeMenu(MENU_CLUB_HOME, 'Allures.php'),
    makePageTitle("Athletic Club Angerien")
]);
$body->addChild($topBar);

// image banner

$body->addChild(makeClubBanner("../ClubImages/Stopwatch.png"));


$article = $body->makeChild('article');
$table = $article->makeChild('table', '', ['class' => 'tableStyleTrackIntervals']);
$tr = $table->makeChild('tr');
$tr->makeChild('th', 'dernier temps des 10 km');
$td = $tr->makeChild('th', "tableau des rythmes pour l'entraînement");
$odd = false;
$rangeOfLast10kTimes = range(35, 70);
$rangeOfAllures = [5, 10, 21, 42];
foreach ($rangeOfLast10kTimes as $last10kTime) {
    $style = $odd ? 'background-color:rgb(245,230,245);' : '';
    $odd = !$odd;
    $athlete = new Athlete($last10kTime);
    $tr = $table->makeChild('tr', '', ['style' => $style]);
    $tr->makeChild('td', $last10kTime, ['style' => 'width:30%;']);
    $td = $tr->makeChild('td');
    $subTable = $td->makeChild('table', '', ['class' => 'tableStyleTrackIntervals', 'style' => 'font-size:0.666em;']);
    $subTable->makeChild('th', 'Allure');
    $subTable->makeChild('th', 'sec/100m');
    $subTable->makeChild('th', 'min/tour');
    $subTable->makeChild('th', 'min/km');
    $subOdd = false;
    foreach ($rangeOfAllures as $allure) {
        $style = $subOdd ? 'background-color:rgb(245,230,245);' : 'background-color:white;';
        $subOdd = !$subOdd;
        $tr = $subTable->makeChild('tr', '', ['style' => $style]);
        $tr->makeChild('td', $allure);
        $tr->makeChild('td', Tools::decimalMinutesToHMSstring(0.1 * $athlete->predictedRacePaceFromDistance($allure)));
        $tr->makeChild('td', Tools::decimalMinutesToHMSstring(0.4 * $athlete->predictedRacePaceFromDistance($allure)));
        $tr->makeChild('td', Tools::decimalMinutesToHMSstring($athlete->predictedRacePaceFromDistance($allure)));
    }
}


$body->addChild(makeFooter());

//$body->makeChild('footer', '', ['class' => 'footer', 'style' => 'height:100vh;']);

$html->echo();

