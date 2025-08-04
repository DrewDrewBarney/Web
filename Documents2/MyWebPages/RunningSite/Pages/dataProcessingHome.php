<?php

session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once '../../FIT/FIT_all.php';

include_once 'menu.php';
UserManagement::protect();

$lowestRunningSpeed = 1.6666666; // around 16 minutes per mile
$lowestRunningCadence = 100;
$lowestRunningPower = 50;
$lowestAcceptableR2 = 0.6;

$savePacePowerRelationship = Tag::make('button', 'Save for pace adjustment with app', ['name' => 'savePacePowerRelationship', 'value' => 'pressed']);
$slope = Tag::make('input', '', ['name' => 'slope', 'style' => 'visibility:hidden;']);
$intercept = Tag::make('input', '', ['name' => 'intercept', 'style' => 'visibility:hidden;']);

list($html, $head, $body) = makePage('FIT/TCX File Processing');

$topBar = makeTopBar();
$topBar->addChildren([
    makeMenu(MENU_HOME, 'tools.php'),
    makePageTitle('Tools'),
    UserManagement::loggedIn() ? makeMenu(MENU_TOOLS_LOGGED_IN, 'dataProcessingHome.php') : makeMenu(MENU_TOOLS_LOGGED_OUT),
    makePageTitle('Analyse Data')
]);
$body->addChild($topBar);

$postKey = 'fileMetaData';
$thisFormAction = 'dataProcessingHome.php';

$form = $body->makeChild('form');
$trainingArticle = $form->makeChild('article');

$trainingArticle->makeChild('h2', 'Power and Cadence vs. Speed. ');

$trainingArticle->makeChild("img", "", ["src" => "../Images/Stopwatch.png", "alt" => "Track", "width" => "40%", "style" => "float:right; margin:30px;"]);

$trainingArticle->makeChild('p',
        "This page uploads a .FIT or .TCX file and plots running power vs. speed and cadence vs. speed. " .
        "It then performs linear regression on the data. " .
        "For the calculated result of this regression to be meaninful <b>the run should: </b>"
);

$ul = $trainingArticle->makeChild('ul');
$ul->makeChild('li', '<b>take place on the flat such as on a track.</b>');
$ul->makeChild('li', '<b>include a variety of sustained paces.</b>');

$trainingArticle->makeChild('p',
        'The simple linear equation obtained allows the calculation of: '
);

$ul = $trainingArticle->makeChild('ul');
$ul->makeChild('li', '<b>the flat running equivalent power for a given pace.</b>');
$ul->makeChild('li', '<b>the flat running equivalent pace for a given power.</b>');

$trainingArticle->makeChild('p',
        'If you enter a recent best flat 10k time it will calculate the equivalent average power produced for this race and from this ' .
        ' the equivalent average power you might reasonably expect to achieve over 5k, half and full marathon'
);

//////////////////////////////
// GET FILE OF PARTICULAR TYPE
//////////////////////////////
//
//$form = $trainingArticle->makeChild('form');

$raceTime = UserManagement::loggedIn() ? UserData::get('', 'last10kTime') : '';

$lastFlat10kTimeInuput = $trainingArticle->makeChild('input', ' recent best flat 10k time (mm:ss)', ['type' => 'text', 'name' => 'last10kTime', 'value' => $raceTime]);

//$radio = makeRadio(['FIT File' => '.fit', 'TCX File' => '.tcx'], '.fit');
//$form->addChild($radio);
//$accept = $radio->value();
$trainingArticle->makeChild('h5', 'Choose a *.fit or a *.tcx file to process');
$files = chooseFile($form, $trainingArticle, '.fit,.tcx,.FIT,.TCX', 'Process file');

//$html->echo();
/*
  echo 'radioButton name = ' . $radioButtons->name() . ' - radioButton value = ' . $radioButtons->value() . '<br>';
  //print_r($files);
  echo '<br>';
  echo 'POST <br>';
  foreach ($_POST as $key => $value) {
  echo $key . ' = ' . $value . '<br>';
  }
  exit;
 * 
 */

// IF FILE GOTTEN THEN PROCESS
//////////////////////////////

if ($files) {

    if (UserManagement::loggedIn()) {
        UserData::set('', 'last10kTime', $lastFlat10kTimeInuput->value());
    }

    $extension = pathInfo($files['name'], PATHINFO_EXTENSION);
    $filename = $files['tmp_name'];

    if ($filename) {

        $processor = null;

        //$pathInfo = pathinfo($filename);
        switch ($extension) {
            case 'tcx':
                $processor = new TCXprocessor($filename);
                break;
            case 'fit';
                $processor = new FITprocessor($filename);
                break;

            default:
                break;
        }

        if ($processor) {

            try {
                $processor->process();
            } catch (Exception $e) {
                echo '<h1>' . $e . '</h1>';
            }

            // POWER VS SPEED
            /////////////////

            $powerVsSpeedChart = new GoogleChart($head, 'chartPowerSpeed');
            $powerVsSpeedChart->setTitle('Power vs. Speed');
            $powerVsSpeedChart->setHAxis(['title' => '"Speed in km/h"']);
            $powerVsSpeedChart->setVAxis(['title' => '"Power in Watts"']);
            $powerVsSpeedChart->addTrendine(['visibleInLegend' => '"true"']);
            $powerVsSpeedChart->setChartStyle(['style' => '"scatter"']);
            $powerVsSpeedChart->addSeriesStyle(['pointSize' => '4', 'lineWidth' => '0']);

            $processor->first();
            while (!$processor->beyond()) {
                list($speed, $power) = $processor->readPair('Speeds', 'Watts');
                if ($speed > $lowestRunningSpeed & $power > $lowestRunningPower) {
                    if ($power < 0xFFFF) {
                        $powerVsSpeedChart->addPair([3.6 * $speed, $power]);
                    }
                }
            }

            $trainingArticle->makeChild('h3', 'Power vs. Speed. ');

            $chartDiv = $powerVsSpeedChart->makeChart();
            $trainingArticle->addChild($chartDiv);

            if ($powerVsSpeedChart->size() > 0) {
                list($b0, $b1, $r2) = $powerVsSpeedChart->regress();
                $b0 = round($b0, 2);
                $b1 = round($b1, 2);
                $r2 = round($r2, 2);

                $trainingArticle->makeChild('h5', sprintf("The slope is %0.2f, intercept %0.2f with an R<sup>2</sup> of %0.2f", $b1, $b0, $r2));
                if ($r2 < $lowestAcceptableR2) {
                    $trainingArticle->makeChild('p', "Poor correlation, R<sup>2</sup> is less than $lowestAcceptableR2", ['class' => 'error']);
                } else {
                    $trainingArticle->makeChild('p', "Note that this power speed relationship is only valid for a particular sensor as there is no standard for running power. "
                            . "You must ensure that you use the sensor in training from which this data was taken.");

                    // CHOOSE TO SAVE POWER TO PACE RELATIONSHIP
                    if ($r2 >= 0.6) {
                        $slope->setAttribute('value', $b1);
                        $intercept->setAttribute('value', $b0);
                        $trainingArticle->addChild($savePacePowerRelationship);
                        $trainingArticle->addChild($slope);
                        $trainingArticle->addChild($intercept);
                    }

                    $tenKtime = Tools::decimalMinutesFromString($lastFlat10kTimeInuput->value());

                    if ($r2 >= 0.6 && $tenKtime > 0) {
                        $athlete = new Athlete($tenKtime);
                        $table = $trainingArticle->makeChild('table', '', ['class' => 'tableStyleRacePowers']);
                        $tr = $table->makeChild('tr');
                        $tr->makeChild('th', 'Race Distance');
                        $tr->makeChild('th', 'Power in Watts');
                        foreach (['5k' => 5, '10k' => 10, '21.1' => 21.1, '42.2' => 42.2] as $caption => $distance) {
                            $tr = $table->makeChild('tr');
                            $power = round($b1 * 60 * $athlete->predictedRaceSpeedFromDistance($distance) + $b0);
                            $tr->makeChild('td', $caption);
                            $tr->makeChild('td', $power);
                        }
                    }
                }
            }

            /*
              // HR VS SPEED
              //////////////

              $chartHRvsSpeed = new GoogleChart($head, 'chartHRSpeed');
              $chartHRvsSpeed->setTitle('HR vs. Speed');
              $chartHRvsSpeed->setHAxis(['title' => '"Speed in km/h"']);
              $chartHRvsSpeed->setVAxis(['title' => '"HR in Watts"']);
              $chartHRvsSpeed->addTrendine(['visibleInLegend' => '"true"']);
              $chartHRvsSpeed->setChartStyle(['style' => '"scatter"']);
              $chartHRvsSpeed->addSeriesStyle(['pointSize' => '4', 'lineWidth' => '0']);

              $processor->first();
              while (!$processor->beyond()) {
              list($speed, $hr) = $processor->readPair('Speeds', 'HRs');
              if ($speed > $lowestRunningSpeed ) {
              $chartHRvsSpeed->addPair([3.6 * $speed, $hr]);
              }
              }

              $chartDiv = $chartHRvsSpeed->makeChart();
              $trainingArticle->makeChild('h3', 'Heart Rate vs. Speed. ');

              $trainingArticle->addChild($chartDiv);
              list($b0, $b1, $r2) = $chartHRvsSpeed->regress();

              $trainingArticle->makeChild('h5', sprintf("The slope is %0.2f, intercept %0.2f with an R<sup>2</sup> of %0.2f", $b1, $b0, $r2));
              if ($r2 < $lowestAcceptableR2) {
              $trainingArticle->makeChild('p', "Poor correlation, R<sup>2</sup> is less than $lowestAcceptableR2", ['class' => 'error']);
              }
              $trainingArticle->makeChild('p', "Note that this power speed relationship is only valid for a particular sensor as there is no standard for running power. "
              . "You must ensure that you use the sensor in training from which this data was taken.");
             * 
             */



            // CADENCE VS SPEED
            ///////////////////
            $cadenceVsSpeedChart = new GoogleChart($head, 'chartCadenceSpeed');
            $cadenceVsSpeedChart->setTitle('Cadence vs. Speed');
            $cadenceVsSpeedChart->setHAxis(['title' => '"Speed in km/h"']);
            $cadenceVsSpeedChart->setVAxis(['title' => '"Cadence"']);
            $cadenceVsSpeedChart->addTrendine(['visibleInLegend' => '"true"']);
            $cadenceVsSpeedChart->addSeriesStyle(['pointSize' => '4', 'lineWidth' => '0', 'legend' => '"none"']);

            $processor->first();
            while (!$processor->beyond()) {
                list($speed, $cadence) = $processor->readPair('Speeds', 'Cads');
                $cadence *= 2;
                if ($speed > $lowestRunningSpeed && $cadence > $lowestRunningCadence) {
                    $cadenceVsSpeedChart->addPair([3.6 * $speed, $cadence]);
                }
            }

            $trainingArticle->makeChild('h3', 'Cadence vs. Speed. ');
            $trainingArticle->addChild($cadenceVsSpeedChart->makeChart());

            if ($cadenceVsSpeedChart->size() > 0) {
                list($b0, $b1, $r2) = $cadenceVsSpeedChart->regress();
                $b0 = round($b0, 2);
                $b1 = round($b1, 2);
                $r2 = round($r2, 2);

                $trainingArticle->makeChild('h5', sprintf("The slope is %0.2f, intercept %0.2f with an R<sup>2</sup> of %0.2f", $b1, $b0, $r2));
                if ($r2 < $lowestAcceptableR2) {
                    $trainingArticle->makeChild('p', "Poor correlation, R<sup>2</sup> is less than $lowestAcceptableR2", ['class' => 'error']);
                } else {

                    $tenKtime = Tools::decimalMinutesFromString($lastFlat10kTimeInuput->value());

                    if ($r2 >= 0.6 && $tenKtime > 0) {
                        $athlete = new Athlete($tenKtime);
                        $table = $trainingArticle->makeChild('table', '', ['class' => 'tableStyleRacePowers']);
                        $tr = $table->makeChild('tr');
                        $tr->makeChild('th', 'Race Distance');
                        $tr->makeChild('th', 'Cadence');
                        foreach (['5k' => 5, '10k' => 10, '21.1' => 21.1, '42.2' => 42.2] as $caption => $distance) {
                            $tr = $table->makeChild('tr');
                            $cadence = round($b1 * 60 * $athlete->predictedRaceSpeedFromDistance($distance) + $b0);
                            $tr->makeChild('td', $caption);
                            $tr->makeChild('td', $cadence);
                        }
                    }
                }
            }

            if ($powerVsSpeedChart->size() > 0) {
                list($a0, $a1, $r2) = $powerVsSpeedChart->regress();
                $tenKtime = Tools::decimalMinutesFromString($lastFlat10kTimeInuput->value());
                if ($r2 > 0.6 && $tenKtime > 0) {

                    $trainingArticle->makeChild('h2', 'Conversion Table of Flat Pace vs. Power');
                    $table = $trainingArticle->makeChild('table', '', ['class' => 'tableStyleRacePowers']);
                    $tr = $table->makeChild('tr');
                    $tr->makeChild('th', 'Pace (m:s/km)');
                    $tr->makeChild('th', 'Power');
                    foreach (range(8, 15, 0.1) as $speed) {
                        $power = round($a0 + $a1 * $speed);
                        $pace = Tools::decimalMinutesToHMSstring(60.0 / $speed);
                        $tr = $table->makeChild('tr');
                        $tr->makeChild('td', $pace);
                        $tr->makeChild('td', $power);
                    }
                }
            } else {
                $trainingArticle->makeChild('div', 'Not a valid file type', ['class' => 'error']);
            }
        }
    }
}



if ($savePacePowerRelationship->pressed()) {
    $domain = UserData::PACE_VS_POWER;
    UserData::set($domain, 'slope', $slope->value());
    UserData::set($domain, 'intercept', $intercept->value());
    //Tools::delayedPopup('saved and will now be available when the next workout is sent to the Marathon Planning App', 1000);
    $body->makeChild('div', 'saved and will now be available when the next workout is sent to the Marathon Planning App', ['class'=>'transientWindow']);
}

$body->addChild(makeFooter());


$html->echo();



//print_r($_POST);


//echo '<h1>-> ' . UserData::get('paceToPowerCorrelation', 'slope') . '</h1>';

