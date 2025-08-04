<?php

include_once 'PHP/all.php';

$lowestRunningSpeed = 1.6666666; // around 16 minutes per mile
$lowestRunningCadence = 100;
$lowestRunningPower = 50;
$lowestAcceptableR2 = 0.6;

list($html, $head, $body) = makePage('TCX Processing', ['Home' => 'index.php']);

$postKey = 'fileMetaData';
$thisFormAction = 'dataProcessingHome.php';

$article = $body->makeChild('article');

$article->makeChild('h1', 'Power and Cadence vs. Speed. ');

$article->makeChild("img", "", ["src" => "Images/Stopwatch.png", "alt" => "Track", "width" => "40%", "style" => "float:right; margin:30px;"]);

$article->makeChild('p',
        "This page uploads a TCX file and plots power in Watts and cadence vs. speed. " .
        "It fits a line to the data as the relationship between cadence vs. speed and power vs. speed is approximately linear. " .
        "The resulting two coefficients may then be used with my Garmin Connect IQ field for the track. ");
$article->makeChild('p',
        "It should be used from data obtained at various running speeds on a track for the correlation to be valid. " .
        "An R<sup>2</sup> of more than $lowestAcceptableR2 indicates a reasonable dataset for the correlation. " .
        "If less than $lowestAcceptableR2 you probably need to get a more varied running session on the track with efforts in a variety of pace/power zones. "
);
$article->makeChild('p',);

$article->addChild(makeTempFilePathGetter($postKey, $thisFormAction, 'Select a TCX file to upload:'));

if (key_exists($postKey, $_SESSION)) {

    $filename = $_SESSION[$postKey];

    if ($filename) {


        $processor = new TCXprocessor($filename);

        $processor->run();

        /////////

        $chart = new GoogleChart('chartPowerSpeed');
        $chart->setTitle('Power vs. Speed');
        $chart->setXlabel('Speed in m/s');
        $chart->setYlabel('Power in Watts');

        $processor->first();
        while (!$processor->beyond()) {
            list($speed, $power) = $processor->readPair('Speeds', 'Watts');
            if ($speed > $lowestRunningSpeed & $power > $lowestRunningPower) {
                $chart->addPair([$speed, $power]);
            }
        }

        $head->addChild($chart->makeHeadScript());
        $chartDiv = $chart->makeChart();
        $article->addChild($chartDiv);
        list($b0, $b1, $r2) = $chart->regress();

        $article->makeChild('h3', sprintf("The slope is %0.2f, intercept %0.2f with an R<sup>2</sup> of %0.2f", $b1, $b0, $r2));
        if ($r2 < $lowestAcceptableR2) {
            $article->makeChild('p', "Poor correlation, R<sup>2</sup> is less than $lowestAcceptableR2", ['class' => 'error']);
        }
        $article->makeChild('p', "Note that this power speed relationship is only valid for a particular sensor as there is no standard for running power. "
                . "You must ensure that you use the sensor in training from which this data was taken.");

        /*
          $chart2 = new GoogleChart('chartHRSpeed');
          $chart2->setTitle('HR vs. Speed');
          $chart2->setXlabel('Speed in m/s');
          $chart2->setYlabel('HR');

          $processor->first();
          $x=0;
          while ($x < 100) {
          $chart2->addPair([$x, $x + rand(-10,10)]);
          $x += 1;
          }

          $head->addChild($chart2->makeHeadScript());

          $article->addChild($chart2->makeChart());
          list($b0, $b1, $r2) = $chart2->regress();
          $article->makeChild('h3', sprintf("Intercept is %0.2f, slope is %0.2f, R2 = %0.2f", $b0, $b1, $r2));

          //////
         * 
         */

        $chart3 = new GoogleChart('chartCadenceSpeed');
        $chart3->setTitle('Cadence vs. Speed');
        $chart3->setXlabel('Speed in m/s');
        $chart3->setYlabel('Cadence');

        $processor->first();
        while (!$processor->beyond()) {
            list($speed, $cadence) = $processor->readPair('Speeds', 'Cads');
            $cadence *= 2;
            if ($speed > $lowestRunningSpeed && $cadence > $lowestRunningCadence) {
                $chart3->addPair([$speed, $cadence]);
            }
        }

        $head->addChild($chart3->makeHeadScript());
        $article->addChild($chart3->makeChart());
        list($b0, $b1, $r2) = $chart3->regress();
        $article->makeChild('h3', sprintf("The slope is %0.2f, intercept %0.2f with an R<sup>2</sup> of %0.2f", $b1, $b0, $r2));
        if ($r2 < $lowestAcceptableR2) {
            $article->makeChild('p', "Poor correlation, R<sup>2</sup> is less than $lowestAcceptableR2", ['class' => 'error']);
        }
    }
}



$body->addChild(makeFooter());

$html->echo();
