<?php

session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

UserManagement::protect();

list($html, $head, $body) = makePage('Track Repetition Pace Calculator', ['home' => 'index.php']);
$topBar = makeTopBar();
$topBar->addChildren([
    makeMenu(MENU_HOME, 'tools.php'),
    makePageTitle('Tools'),
    UserManagement::loggedIn() ? makeMenu(MENU_TOOLS_LOGGED_IN, 'intervalPaces.php') : makeMenu(MENU_TOOLS_LOGGED_OUT),
    makePageTitle('Interval and Repetition Paces')
]);
$body->addChild($topBar);

$trainingArticle = $body->makeChild("article");
$trainingArticle->makeChild('h2', 'Interval and Repetition Paces');
$trainingArticle->makeChild("img", "", ["src" => "../Images/Stopwatch.png", "alt" => "Track", "width" => "40%", "style" => "float:right; margin:30px;"]);

$trainingArticle->makeChild("p", "
    These paces are designed for multiple repetitions of these distances and so 
    are much slower than the corresponding race paces.
    It is so easy to overdo it on the track. This is counterproductive
    as excessive stress breaks down rather than builds up the necessary adaptations to 
    run longer and faster.
");

$trainingArticle->makeChild("p", "
    Intervals have a shorter recovery period, typically accounting for around one third of the time.
    Repetitions are more intense but with a longer recovery amounting to an equal split of time between the active step and the recovery.
");

$trainingArticle->makeChild("p", "
    We are competititive or we wouldn't be here. It is important not to get drawn 
    into competing on the track as this defeats the object, which is to train.
    Track training is all about consistency and control.
");

$trainingArticle->makeChild('p', "
    Training intensity typically varies over two or three week cycles to create
    variety, to stress and recover over a longer timescale than the usual weekly
    routine. I have therefore added paces for Easy, Normal and Hard weeks.
    "
);

$form = $trainingArticle->makeChild("form", '', ['action' => '']);
$form->makeChild("label", "Most recent 10k time (mm:ss)", ["for" => "last10kTime", "style" => "float:none;"]);
$last10kTime = $form->makeChild("input", "", ["type" => "text", "id" => "last10kTime", "name" => "last10kTime"]);

if ($last10kTime->value()) {
    UserData::set('', $last10kTime->name(), $last10kTime->value());
} else if (UserData::get('', $last10kTime->name())) {
    $last10kTime->setValue(UserData::get('', $last10kTime->name()));
}

$form->makeChild("button", "update");

$athlete = new Athlete(floatval($last10kTime->value()));
//function periodicTrainingData(string $type, float $duration, float $oneRepDistance): array {


/*
 * INTERVAL PACES
 */

$trainingArticle->makeChild('h3', 'Interval Paces');

foreach (['Normal Week' => [Workout::normal, 'lightgreen'], 'Hard Week' => [Workout::hard, 'pink'], 'Easy Week' => [Workout::easy, 'lightblue']] as $key => $attributes) {

    list($severity, $color) = $attributes;

    $table = $trainingArticle->makeChild("table", "", ["class" => "intervalPaces"]);

    $table->makeChild('tr')->makeChild('th', $key, ['colspan' => 4, 'style' => "color:$color;"]);

  $titleRow = $table->makeChild("tr");
    $titleRow->makeChild("th", "meters");
    $titleRow->makeChild('th', 'per 100m');
    $titleRow->makeChild("th", "per 400m");
    $titleRow->makeChild("th", "mins/km");
    
    if ($last10kTime->floatVal()) {

        foreach ([100, 200, 300, 400, 500, 600, 800, 1000, 1200, 1500] as $meters) {
            $km = $meters / 1000;
            $workout = Workout::makeTrackWorkout($last10kTime->floatVal(), $km, Workout::intervalDuration, $severity);

            $row = $table->makeChild("tr");
            $row->makeChild("td", $meters);
            $pace = $workout->activePace();

            $row->makeChild("td", Tools::decimalMinutesToHMSstring($pace / 10));
            $row->makeChild("td", Tools::decimalMinutesToHMSstring($pace / 2.5));
            $row->makeChild("td", Tools::decimalMinutesToHMSstring($pace));
        }
    }
}

/*
 * REPETITION PACES
 */

$trainingArticle->makeChild('h3', 'Repetition Paces');

foreach (['Normal Week' => [Workout::normal, 'lightgreen'], 'Hard Week' => [Workout::hard, 'pink'], 'Easy Week' => [Workout::easy, 'lightblue']] as $key => $attributes) {

    list($severity, $color) = $attributes;

    $table = $trainingArticle->makeChild("table", "", ["class" => "intervalPaces"]);

    $table->makeChild('tr')->makeChild('th', $key, ['colspan' => 4, 'style' => "color:$color;"]);

    $titleRow = $table->makeChild("tr");
    $titleRow->makeChild("th", "meters");
    $titleRow->makeChild('th', 'per 100m');
    $titleRow->makeChild("th", "per 400m");
    $titleRow->makeChild("th", "mins/km");

    if ($last10kTime->floatVal()) {

        foreach ([100, 200, 300, 400, 500, 600, 800, 1000, 1200, 1500] as $meters) {
            $km = $meters / 1000;
            $workout = Workout::makeRepetitionWorkout($last10kTime->floatVal(), $km, Workout::repetitionDuration, $severity);

            $row = $table->makeChild("tr");
            $row->makeChild("td", $meters);
            $pace = $workout->activePace();
            $row->makeChild("td", Tools::decimalMinutesToHMSstring($pace / 10));
            $row->makeChild("td", Tools::decimalMinutesToHMSstring($pace / 2.5));
            $row->makeChild("td", Tools::decimalMinutesToHMSstring($pace));
        }
    }
}



$body->addChild(makeFooter());

$html->echo();

//showGetPost();
//echo $last10kTime->value();

