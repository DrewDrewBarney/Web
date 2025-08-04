<?php

session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';
UserManagement::protect();

list($html, $head, $body) = makePage('Track Intervals Pace Calculator');

$topBar = makeTopBar();
$topBar->addChildren([
    makeMenu(MENU_HOME, 'tools.php'),
    makePageTitle('Tools'),
    UserManagement::loggedIn() ? makeMenu(MENU_TOOLS_LOGGED_IN, 'racePredictor.php') : makeMenu(MENU_TOOLS_LOGGED_OUT),
    makePageTitle('Race Predictor')
]);
$body->addChild($topBar);

$trainingArticle = $body->makeChild("article");
$trainingArticle->makeChild('h2', 'Race Predictor');
$trainingArticle->makeChild("img", "", ["src" => "../Images/Stopwatch.png", "alt" => "Track", "width" => "40%", "style" => "float:right; margin:30px;"]);
$para = $trainingArticle->makeChild("p",
        "These race predictions are based on Riegel's formula<sup>1</sup>. " .
        "I believe these in turn are based on flat racing records. " .
        "Common sense has to be used in their interpretation. " .
        "Actual race times will depend on many factors such as the weather, the terrain and of course how you feel on the day. "
);

$para->makeChild('a', 'Peter Riegel', ['href' => "https://en.wikipedia.org/wiki/Peter_Riegel", 'class' => 'buttonRight']);

$form = $trainingArticle->makeChild("form",'',['id'=>'theForm']);
$form->makeChild("label", "Most recent 10k time (mm:ss)", ["for" => "last10kTime", "style" => "float:none;"]);
$last10kTime = $form->makeChild("input", "", ["type" => "text", "id" => "last10kTime", "name" => "last10kTime"]);

if ($last10kTime->value()){
    UserData::set('', $last10kTime->name(), $last10kTime->value());
} else if (UserData::get('', $last10kTime->name())){
    $last10kTime->setValue(UserData::get('', $last10kTime->name()));
}

$form->makeChild("button", "update");

$riegel = new Riegel(Tools::decimalMinutesFromString($last10kTime->value()), 10);

$table = $trainingArticle->makeChild('table', '', ["class" => "intervalPaces"]);
$tr = $table->makeChild('tr');
$tr->makeChild('th', 'Race Distance / km');
$tr->makeChild('th', 'Predicted Time h:m:ss');

foreach ([5, 10, 15, 21.1, 42.2] as $distance) {
    $mins = $riegel->raceTimeForDistance($distance);
    $prediction = Tools::decimalMinutesToHMSstring($mins);
    $prediction = $distance <= 21.1 ? $prediction : $prediction . ' <sup>2</sup>';
    $prediction = $mins ? $prediction : '';
    
    $tr = $table->makeChild('tr');
    $tr->makeChild('td', $distance);
    $tr->makeChild('td', $prediction);
}

$trainingArticle->makeChild('p', "1. this is predicted from Riegel's formula below.");
$para = $trainingArticle->makeChild('p', "2. predictions based on Reigel's formula for distances over a half-marathon will be overly optimistic. "
        . "For this distance you should use ");
$para->makeChild('a','Critical Pace', ['href'=>'criticalPace.php', 'class'=>'button']);

$trainingArticle->addChild(
        mountFormulaOnCard(
                makeEquation(
                        [
                            'T<sub>2</sub>', '=',
                            'T<sub>1</sub>', 'x',
                            makeBrace('['),
                            makeFrac('D<sub>2</sub>', 'D<sub>1</sub>'),
                            makeBrace(']'),
                            '<sup>1.06</sup'
                        ]
                ),
                'T<sub>1</sub> is a recent race time; D<sub>1</sub> is a recent race distance; D<sub>2</sub> the target race distance; T<sub>2</sub> the predicted race time'
        )
);

$body->addChild(makeFooter());

$html->echo();

