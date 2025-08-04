<?php

$body->setAttribute('onload', Tools::$restoreScrollPosition);

// image banner

$src =  clientDocumentRoot() . "RunningSite/Images/Zwift.png";
$body->makeChild("img", "", ["src" => $src, "alt" => "Critical", "style" => "float:right; margin: 5ch 17ch 5ch 5ch; width: 20%;"]);

$schemaArticle = $body->makeChild("article");

/*
 * BUILD THE INPUT FORM FOR THE NUMBER OF PHASES, TYPES AND PARAMETERS
 */

$form = $schemaArticle->makeChild('form', '', ['id' => 'phasesForm']);

$form->makeChild('h2', 'Workout Designer');
//$form->makeChild('p', '(hors échauffement et retour au calme)');

$commonDivStyle = ['style' => 'margin: 3ch 0ch;'];
$last10kTime = $form->makeChild('div', '', $commonDivStyle)->makeChild('input', ' last 10k time', ['name' => 'last10kTime', 'type' => 'string']);

if ($last10kTime->value()) {
    UserData::set('', $last10kTime->name(), $last10kTime->value());
} else if (UserData::get('', $last10kTime->name())) {
    $last10kTime->setValue(UserData::get('', $last10kTime->name()));
}

$athlete = new Athlete(floatval($last10kTime->value()));
$milePace = $athlete->predictedRacePaceFromDistance(Constants::MILE_LENGTH_IN_KILOMETERS);

/*
 * GET THE NUMBER OF PHASES
 */

$numberOfPhasesInput = $form->makeChild('div', '', $commonDivStyle)->makeChild('input', ' nombre de phases',
        ['type' => 'number', 'name' => "numberOfPhases", 'value' => '1', 'min' => '1', 'max' => '40',
            'onchange' => "document.getElementById('phasesForm').submit();"
        ]);

$numberOfPhases = $numberOfPhasesInput->intVal() ? $numberOfPhasesInput->intVal() : 1;

/*
 * BUILD THE TABLE ON THE FORM
 */

$table = $form->makeChild('table', '', ['class' => 'tableStyleTrackIntervalsForm']);

$titleRow = $table->makeChild('tr');

$titleRow->makeChild('th', 'Step Type');
$titleRow->makeChild('th', 'Reps');
$titleRow->makeChild('th', 'Active Distance');
$titleRow->makeChild('th', 'AS');
$titleRow->makeChild('th', 'Pace');
$titleRow->makeChild('th', 'Recovery Distance');

$controls = [];
$phaseTypes = [
    //'' => ZwiftWorkout::$warmup,
    'Warm Up' => ZwiftWorkout::$warmup,
    'Steady State' => ZwiftWorkout::$steady,
    'Intervals' => ZwiftWorkout::$intervals,
    'Cool Down' => ZwiftWorkout::$cooldown,
];

for ($i = 0; $i < $numberOfPhases; $i++) {
    $row = $table->makeChild('tr');

    /*
     * MAKE THE PHASE TYPE SELECTOR
     */

    $name = "select_$i";
    $selectWorkoutFocus = $row->makeChild('td')->makeChild('select', '', ['name' => $name, 'value'=>'Warm Up', 'onchange' => "document.getElementById('phasesForm').submit();"]);
    $controls[$name] = $selectWorkoutFocus;
    foreach ($phaseTypes as $phaseType => $zwiftPhaseType) {
        $option = $selectWorkoutFocus->makeChild('option', $phaseType);
        if ($phaseType === $selectWorkoutFocus->value()) {
            $option->setAttributes(['selected' => 'selected']);
        }
    }

    /*
     * BUILD THE REQUIRED ELEMENTS BASED ON THE PHASE TYPE SELECTED
     */
    switch ($selectWorkoutFocus->value()) {

        case 'Warm Up':
        case 'Cool Down':

            //no reps
            $row->makeChild('td');

            // the active distance
            $controls["activeDistance_$i"] = $row->makeChild('td')->makeChild('input', ' m', ['type' => 'number', 'name' => "activeDistance_$i", 'min' => '100', 'max' => '42000', 'step' => '100', 'value' => '400']);

            // no allure
            $row->makeChild('td');

            // no pace
            $row->makeChild('td');

            // no recovery distance
            $row->makeChild('td');
            break;

        case 'Steady State':

            //no reps
            $row->makeChild('td');

            // the active distance
            $controls["activeDistance_$i"] = $row->makeChild('td')->makeChild('input', ' m', ['type' => 'number', 'name' => "activeDistance_$i", 'min' => '100', 'max' => '42000', 'step' => '100', 'value' => '400']);

            // the allure
            $controls["allure_$i"] = $row->makeChild('td')->makeChild('input', ' as', ['type' => 'number', 'name' => "allure_$i", 'min' => '1', 'max' => '1000', 'value' => '10', 'onchange' => "document.getElementById('phasesForm').submit();"]);

            // the pace
            $row->makeChild('td', Tools::decimalMinutesToHMSstring($athlete->predictedRacePaceFromDistance($controls["allure_$i"]->intVal())));

            // no recovery distance
            $row->makeChild('td');

            break;

        case 'Intervals':
            // the reps
            $controls["reps_$i"] = $row->makeChild('td')->makeChild('input', '', ['type' => 'number', 'name' => "reps_$i", 'min' => '1', 'max' => '50', 'value' => '1']);

            // the active distance
            $controls["activeDistance_$i"] = $row->makeChild('td')->makeChild('input', ' m', ['type' => 'number', 'name' => "activeDistance_$i", 'min' => '100', 'max' => '42000', 'step' => '100', 'value' => '400']);

            // the allure
            $controls["allure_$i"] = $row->makeChild('td')->makeChild('input', ' as', ['type' => 'number', 'name' => "allure_$i", 'min' => '1', 'max' => '1000', 'value' => '10', 'onchange' => "document.getElementById('phasesForm').submit();"]);

            // the pace
            $row->makeChild('td', Tools::decimalMinutesToHMSstring($athlete->predictedRacePaceFromDistance($controls["allure_$i"]->intVal())));

            // the recovery distance
            $controls["recoveryDistance_$i"] = $row->makeChild('td')->makeChild('input', ' m', ['type' => 'number', 'name' => "recoveryDistance_$i", 'min' => '100', 'max' => '2000', 'step' => '100', 'value' => '200']);
            break;
    }
}


$zwiftArticle = $body->makeChild('article');
$zwiftArticle->makeChild('h2', 'Zwift Workout');
$zwiftArticle->makeChild('p',
        "Once created you can select the workout text, copy and paste into an open empty Zwift workout file (*.zwo)
         on your desktop computer. On my device it is stored in a sub-directory off my document file /Zwift/Workouts/123456
         When Zwift is then run on that device it should pick up the workout file and put it in
         the data for you on its server. It should then appear under custom workouts. You can then select the workout on any device.
        ", ['id' => 'zwiftIntro']);

/*
 * CREATE THE ZWIFT WORKOUT
 */


$createButton = $zwiftArticle->makeChild('div', '', $commonDivStyle)->makeChild('button', 'Create', ['name' => 'create', 'value' => 'create', 'form' => 'phasesForm', 'onclick'=>Tools::$saveScrollPosition]);

if ($createButton->pressed()) {

    $zwiftWorkout = new ZwiftWorkout($athlete->last10kTime());

    for ($i = 0; $i < $numberOfPhases; $i++) {

        // map our intuitive control types to Zwift workout phase types
        $key = $controls["select_$i"]->value();
        $phaseType = $phaseTypes[$key];

        $reps = isset($controls["reps_$i"]) ? $controls["reps_$i"]->intVal() : 1;
        $activeDistance = isset($controls["activeDistance_$i"]) ? $controls["activeDistance_$i"]->intVal() : 0;
        $allure = isset($controls["allure_$i"]) ? $controls["allure_$i"]->floatVal() : 600;
        $recoveryDistance = isset($controls["recoveryDistance_$i"]) ? $controls["recoveryDistance_$i"]->intVal() : 0;
        $pace = $athlete->predictedRacePaceFromDistance($allure);

        switch ($phaseType) {
            case ZwiftWorkout::$warmup:
                $zwiftWorkout->addWarmup($activeDistance);
                break;
            case ZwiftWorkout::$steady:
                $zwiftWorkout->addSteady($activeDistance, $pace);
                break;
            case ZwiftWorkout::$intervals:
                $zwiftWorkout->addRep($activeDistance, $pace, $reps, $recoveryDistance);
                break;
            case ZwiftWorkout::$cooldown:
                $zwiftWorkout->addCooldown($activeDistance);
                break;
        }

        //$zwiftWorkout->addPhase($phaseType, $activeDistance, $pace, $reps, $recoveryDistance);
    

    $pre = $zwiftArticle->makeChild('pre', '', ['class' => 'ZwiftWorkoutCard']);
    $work = $zwiftWorkout->toTag();
    $workoutString = $work->toString();
    $work->setLiterality(true);
    $pre->addChild($work);

    // copy to clipboard
    $zwiftArticle->addChild(makeClippingButton($workoutString));
}

}

/*
 * END
 */



$milePaceString = Tools::decimalMinutesToHMSstring(Constants::MILE_LENGTH_IN_KILOMETERS * $milePace) . ' / mile OR ' . Tools::decimalMinutesToHMSstring($milePace) . ' / km';
$zwiftArticle = $body->makeChild('article');
$zwiftArticle->makeChild('h2', "Test of Zwift Mile Pace");
$zwiftArticle->makeChild('h4', "Your race pace over a mile is : $milePaceString");
$zwiftArticle->makeChild('p',
        "I don't think the implementation of running on Zwift to be complete and in my opinion it should be considered in beta though it remains 
            incomplete after many years.
            It uses best mile pace for running workouts with a nominal power of 1.0 at that pace. You have to enter that mile pace via a typically terrible 
            gaming data entry interface. In my case a workout with a phase with a power of 1.0 does not seem to accurately represent the value I entered in user settings.
            I recommend generating a test workout containing a phase of power 1.0 and confirming this results in your mile pace by inspecting the workout
            in Zwift. If it doesn't you may have to spoof the value entered in your user settings on Zwift to ensure the test workout does indeed produce
            your mile pace. It is painful but once done seems set longterm. I would leave the test workout on Zwift and just take a look at that single phase
            of power 1.0 and ensure the mile pace stays correct. Your race pace over a mile based on the 10k time you entered above is $milePaceString
        ");

/*
 * CREATE THE TEST WORKOUT
 */


$createTestButton = $zwiftArticle->makeChild('div', '', $commonDivStyle)->makeChild('button', 'Create', ['name' => 'createTest', 'value' => 'createTest', 'form' => 'phasesForm', 'onclick'=> Tools::$saveScrollPosition]);

if ($createTestButton->pressed()) {

    $zwiftWorkout = new ZwiftWorkout($athlete->last10kTime(), 'Standardisation Test Workout');
    $zwiftWorkout->addStandardPhase();
    $pre = $zwiftArticle->makeChild('pre', '', ['class' => 'ZwiftWorkoutCard']);
    $work = $zwiftWorkout->toTag();
    $workoutString = $work->toString();
    $work->setLiterality(true);

    $pre->addChild($work);

    // copy to clipboard
    $zwiftArticle->addChild(makeClippingButton($workoutString));

}


$body->addChild(makeFooter());

//$html->echo();
