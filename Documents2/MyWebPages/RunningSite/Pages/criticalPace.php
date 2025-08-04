<?php

session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';
UserManagement::protect();


list($html, $head, $body) = makePage('Critical Pace Calculator');

$body->setAttribute('onload', Tools::$restoreScrollPosition);

$topBar = makeTopBar();
$topBar->addChildren([
    makeMenu(MENU_HOME, 'tools.php'),
    makePageTitle('Tools'),
    UserManagement::loggedIn() ? makeMenu(MENU_TOOLS_LOGGED_IN, 'criticalPace.php') : makeMenu(MENU_TOOLS_LOGGED_OUT),
    makePageTitle('Critical Pace Calculator')
]);
$body->addChild($topBar);

$trainingArticle = $body->makeChild("article");

$trainingArticle->makeChild("img", "", ["src" => "../Images/CriticalSpeed.png", "alt" => "Critical", "style" => "width: 30%;", "class" => "center"]);

$heading = $trainingArticle->makeChild('h2', 'Critical Pace Calculator');

$para = $trainingArticle->makeChild("p",
        'This calculator estimates both critical pace and "anaerobic distance" from the results of at least two recent time trials over two different distances.'
);

$para = $trainingArticle->makeChild("h3",
        'Enter recent maximum single effort distances and corresponding times:'
);

$para = $trainingArticle->makeChild("p",
        'The distance should be well above your "anaerobic distance" with a maximum duration of 20 minutes. ' .
        'The distances should be reasonably different ' .
        '(e.g. a series of a minimum of two time trials, one of 2km and one of 4km would be good for most).'
);

$form = $trainingArticle->makeChild("form");

$div = $form->makeChild('div', '', ['class' => 'centerText']);
$div->makeChild('label', 'Trial Distance', ['class' => 'centerText']);
$div->makeChild('label', 'Trial Duration', ['class' => 'centerText']);

$fields = [];
foreach (range(0, 4, 1) as $i) {
    $div = $form->makeChild('div', '', ['class' => 'centerText']);
    $distance = $div->makeChild('input', '', ['type' => 'text', 'name' => "distance_$i"]);
    $duration = $div->makeChild('input', '', ['type' => 'text', 'name' => "duration_$i"]);
    $fields[] = ['distance' => $distance, 'duration' => $duration];
}

$form->makeChild("button", "calculate",['onclick'=>Tools::$saveScrollPosition]);

// CHART OF DISTANCE VS. DURATION - APPROXIMATE LINE - LINEAR REGRESSION

$chart2 = new GoogleChart($head, 'Trial Distance vs. Trial Duration');
$chart2->setTableLabels(["'Duration'", "'Distance'"]);

$chart2->setTitle('Race Distance vs. Race Duration');
$chart2->setVAxis(['title' => "'Distance km'", 'minValue' => '0']);
$chart2->setHAxis(['title' => "'Duration min'", 'minValue' => '0']);
$chart2->setChartStyle(['style' => "'scatter'", 'legend' => '"none"']);
$chart2->addSeriesStyle(['color' => "'blue'", 'lineWidth' => '0', 'pointSize' => '8']);
$chart2->addTrendine();

$maxDistanceEntered = 0;
foreach ($fields as $field) {
    if ($field['distance']->value() != null && $field['duration']->value()) {
        $distance = floatval($field['distance']->value());
        $duration = Tools::decimalMinutesFromString($field['duration']->value());
        $chart2->addPair([$duration, $distance]);
        $maxDistanceEntered = $distance > $maxDistanceEntered ? $distance : $maxDistanceEntered;
    }
}

list($a0, $a1, $R2) = $chart2->regress();

// CHART OF SPEED VS. DISTANCE - APPROX HYPERBOLA
$chart1 = new GoogleChart($head, 'Trial Speed vs. Trial Duration');
$chart1->setTitle('Trial Speed vs. Trial Distance');
$chart1->setVAxis(['title' => "'Speed km/hr'", 'minValue' => '0', 'maxValue' => '20']);
$chart1->setHAxis(['title' => "'Distance km'", 'minValue' => '0']);
$chart1->setChartStyle(['style' => "'scatter'"]);
$chart1->addSeriesStyle(['pointSize' => '8', 'lineWidth' => '0', 'color' => "'blue'"]);
$chart1->addSeriesStyle(['pointSize' => '0', 'lineWidth' => '3', 'color' => "'red'"]);
$chart1->addSeriesStyle(['pointSize' => '0', 'lineWidth' => '3', 'color' => "'orange'"]);
//$chart1->addSeriesStyle(['lineWidth'=>'0', 'color'=>"'magenta'", 'style'=>"'function'"]);

$chart1->setTableLabels(["'Distance'", "'Trials'", "'Predicted Speed'", "'Critical Speed'"]);

$i = 0;
foreach ($fields as $field) {

    if ($field['distance']->value() != null && $field['duration']->value()) {
        $distance = floatval($field['distance']->value());
        $duration = Tools::decimalMinutesFromString($field['duration']->value());
        $speed = $duration ? 60 * $distance / $duration : 0.0;
        $chart1->addPair([$distance, $speed, null, null]);
        $i++;
    }
}

for ($distance = 0; $distance < $maxDistanceEntered; $distance += 0.1) {
    $predictedSpeed = $a0 !==null && $distance >= 2 * $a0 && $distance <= 30 * $a1 ? 60 * ($a1 * $distance) / ($distance - $a0) : null;
    //$predictedSpeed = 60 * ($a1 * $distance) / ($distance - $a0);
    $criticalSpeed = 60 * $a1;
    $chart1->addPair([$distance, null, $predictedSpeed, $criticalSpeed]);
}





if ($a0 && $a1) {


    $trainingArticle->addChild($chart1->makeChart());

    $trainingArticle->makeChild('p', 'N.B. If the trial data points do not lie close to the curve of predicted speed vs. distance '
            . ' the critical speed prediction is likely to be inaccurate.');

    $trainingArticle->addChild($chart2->makeChart());

    // summary data
    $trainingArticle->makeChild('h3', sprintf('Critical speed is %0.2f km/hr', 60 * $a1));
    $trainingArticle->makeChild('h3', 'Critical pace is ' . Tools::decimalMinutesToHMSstring(1.0 / $a1) . ' min/km');
    $trainingArticle->makeChild('h3', sprintf('Anaerobic distance is %0.0f m', 1000 * $a0));
    $trainingArticle->makeChild('h4', sprintf('(R^2 is %0.5f)', $R2));

    // intro

    $trainingArticle->makeChild('h2', 'Marathon Time Prediction from Critical Speed.');

    $trainingArticle->makeChild('p', 'Critical speed corresponds to maximum lactate steady state MLSS (a hair"s whisker below the onset of blood'
            . ' lactate accumulation) and can only be sustained for around 20 minutes. '
            . ' The commonly defined lactate threshold lies below this and typically can be sustained for around 30 minutes.'
            . ' Longer distances require running close to yet below lactate threshold in the sweet spot that maximises speed '
            . ' without using glucose inefficiently (anaerobically with lactate production) and so rapidly depleting stored glycogen. '
    );
    $trainingArticle->makeChild('a', 'study link', ['class' => 'buttonRight', 'href' => 'https://pubmed.ncbi.nlm.nih.gov/32472926/']);

    $trainingArticle->makeChild('p', 'A study of a large dataset (Strava) of runners of various abilities demonstrated a '
            . ' clear relationship between critical speed and race duration.'
            . ' Typically elite runners run with a marathon speed of around 93% of critical speed whereas a typical runner'
            . ' might only run at around 85% of critical speed. '
            . ' The factor that determined the ratio of marathon speed to critical speed was simply race duration, with a clear'
            . ' linear relationship'
    );

    $trainingArticle->makeChild('p', 'The proportion of your critical speed that you can maintain for the marathon determines'
            . ' the duration of your marathon, and therefore the proportion of critcal speed you can maintain...'
            . ' It is possible to solve this circularity and predict your marathon time. '
            . ' Your prediction is highlighted in the table below:');

    // table
    $table = new Table(15, 3, '', ['class' => 'center, tableStyle3']);
    $table->getTR(0)->setAttributes(['style' => 'color:white; background-color:DodgerBlue;']);

    $table->getTD(0, 0)->setInner('Marathon Efficiency');
    $table->getTD(0, 1)->setInner('Marathon Pace');
    $table->getTD(0, 2)->setInner('Marathon Time');
    $predictedMarathonEfficiency = marathonEfficiency($a1);
    $marathonEfficiency = 0.8;
    for ($row = 1; $row < 15; $row++) {
        $table->getTD($row, 0)->setInner(sprintf('%0.2f', $marathonEfficiency));
        $table->getTD($row, 1)->setInner(Tools::decimalMinutesToHMSstring(1.0 / ($a1 * $marathonEfficiency)));
        $table->getTD($row, 2)->setInner(Tools::decimalMinutesToHMSstring(42.2 / ($a1 * $marathonEfficiency)));

        if ($marathonEfficiency >= $predictedMarathonEfficiency && $marathonEfficiency <= $predictedMarathonEfficiency + 0.01) {
            $table->getTR($row)->setAttributes(['style' => 'background-color:Orange;']);
        }

        $marathonEfficiency += 0.01;
    }

    $trainingArticle->addChild($table->getTag());

    $trainingArticle->makeChild('h3', 'Discussion');

    $trainingArticle->makeChild('p', 'It is important to understand the limitations of conclusions drawn from the analysis of big data.'
            . ' This study does not seem to categorise runners with regard to their levels of training.'
            . ' Elite runners heading up a marathon are both genetically privileged and highly trained. '
            . ' Non elite runners have varying genetically determined abilities and levels of training.'
            . " We don't know if they have or have not done the required distance training to ensure sufficient glycogen"
            . " reserves for the marathon. "
            . " We don't know if they have trained sufficiently to alter the shape of their lactate vs. running speed curves."
            . " We would expect both to determine how close to critical pace is sustainable for a marathon.");
} else {
    $trainingArticle->makeChild('p', 'Add at least two data pairs...', ['class'=>'error']);
}

$body->addChild(makeFooter());

$html->echo();

function marathonEfficiency(float $Sc): float {
    $a0 = 1.03071428571;
    $a1 = -0.00067142857;
    $e = ($a0 + sqrt(pow($a0, 2.0) + (4.0 * $a1 * 42.2) / $Sc)) / 2.0;
    return $e;
}
