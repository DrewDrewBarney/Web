<?php
include_once '../../Common/PHP/roots.php';
include_once '../ClubPHP/clubAll.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

$intervalDistances = [0.2, 0.6, 0.3, 0.8, 0.4, 1.0, 0.5, 0.6, 0.2, 0.8, 0.3, 1.0, 0.4, 0.5];
$weekOfYear = (new DateTimeImmutable('now'))->format('W');
$intervalTime = 30;

list($html, $head, $body) = makePage('Athletic Club Angerien', ['clubHome' => 'index.php']);
$topBar = makeTopBar();
$topBar->addChildren([
    makePageTitle("Athletic Club Angerien"),
    makeMenu(MENU_CLUB_HOME, 'ClubIntervalsBuilder.php'),
    makePageTitle('Intervals sur Piste - Semaine ' . $weekOfYear),
    
]);

$body->addChild($topBar);

// image banner
$body->addChild(makeClubBanner("../ClubImages/Stopwatch.png"));


$trainingArticle = $body->makeChild('article');



foreach ([30] as $intervalTime) {
    
    $trainingArticle->makeChild('h2', "Durée $intervalTime min.");
    //$article->makeChild('p',  "Approximate duration of $intervalTime min for each runner irrespective of their speed.");
    $trainingArticle->makeChild('p',  "Durée approximative de $intervalTime min pour chaque coureur quelle que soit sa vitesse.");

    $table = $trainingArticle->makeChild('table', '', ['class' => 'tableStyleTrackIntervals']);

    $row = $table->makeChild('tr');
    $row->makeChild('th', 'Temps 10k');
    $row->makeChild('th', 'Structure');
    $row->makeChild('th', 'Distance');

    foreach (range(35, 65) as $last10ktime) {
        $rowStyle = $last10ktime % 2 ? 'background-color:rgb(250,230,250);' : 'background-color:white;';
        $workout = new Workout($last10ktime);
        $activeStepLength = $intervalDistances[$weekOfYear % sizeof($intervalDistances)];
        
        //$result = Workout::makePeriodicWorkout('intervals', $intervalTime, $last10ktime, $activeStepLength);
        //Workout::makePeriodicWorkout($intervalTime, $periodicTrainingDuration, $last10kTime, $oneIntervalDistance, $severity)
        $result = Workout::makeTrackWorkout($last10ktime, $activeStepLength);

        $row = $table->makeChild('tr','',['style'=>$rowStyle]);
        $row->makeChild('td', $last10ktime);
        $row->makeChild('td', $result->justRepsToString(), ['style'=>'white-space:nowrap;']);
        $row->makeChild('td', sprintf('%0.1f', $result->periodicTrainingDistance()));
    }
}



$body->addChild(makeFooter());

$html->echo();
