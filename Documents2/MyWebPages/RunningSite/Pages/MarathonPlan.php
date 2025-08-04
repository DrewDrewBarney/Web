<?php

session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

UserManagement::protect();


//phpinfo();
//exit();
//const MarathonPlan::DOMAIN = 'MarathonPlan';
//const GLOBAL_DOMAIN = '';

const BOLD = 'font-weight: bold;';

const WORKOUT_TYPE_STYLES = [
    MarathonPlan::WORKOUT_TYPE_BASE_1 => 'background-color:deepskyblue;',
    MarathonPlan::WORKOUT_TYPE_BASE_2 => 'background-color:deepskyblue;',
    MarathonPlan::WORKOUT_TYPE_REPS => 'color:white; background-color:orangered;',
    MarathonPlan::WORKOUT_TYPE_TRACK => 'background-color:violet;',
    MarathonPlan::WORKOUT_TYPE_TEMPO => 'background-color:lime;',
    MarathonPlan::WORKOUT_TYPE_LONG => 'background-color:deepskyblue;',
    MarathonPlan::WORKOUT_TYPE_REST => 'background-color:gainsboro;'
];

////////////////////////////////////////////////////////////////////////////////////////////////
/// MAKE THE MARATHON PLAN PAGE 
////////////////////////////////////////////////////////////////////////////////////////////////


list($html, $head, $body) = makePage('Marathon Plan', ['home' => 'index.php']);

$topBar = makeTopBar();
$topBar->addChildren([
    makeMenu(MENU_HOME, 'tools.php'),
    makePageTitle('Tools'),
    UserManagement::loggedIn() ? makeMenu(MENU_TOOLS_LOGGED_IN, 'MarathonPlan.php') : makeMenu(MENU_TOOLS_LOGGED_OUT),
    makePageTitle('Marathon or Half-Marathon Planner')
]);
$body->addChild($topBar);

/*
 * jump to particular scroll postion
 */


$body->setAttribute('onload', Tools::$restoreScrollPosition);

////////////////////////////////////////////////////////////////////////////////////////////////
/// FORM TO INPUT ATHLETE AND RACE INFORMATION
////////////////////////////////////////////////////////////////////////////////////////////////


$div = Tag::make('div', '', ['style' => 'margin:150px; padding: 50px; border-radius:30px;background-color:white;box-shadow: 5px 5px 30px darkgray;']);

$trainingArticle = $body->makeChild('article', '', ['style' => 'margin:30px;']);

// image

$div = $trainingArticle->makeChild('div', '', ['style' => 'float:right; margin:2ch 0ch 2ch 2ch; padding:6ch;', 'class' => 'wavyBackground']);
$img = $div->makeChild('img', '', ['src' => '../Images/angerien.png', 'style' => 'width:300px; ', 'class' => 'wobble']);

//$article->makeChild('img','',['src'=>'../Images/marathon.png', 'style'=>'float: right; height:50ch; margin:2ch 0ch 2ch 2ch;']);


$trainingArticle->makeChild('h2', 'Event Details');

$form = $trainingArticle->makeChild('form', '', ['id' => 'form']);

$table = $form->makeChild('table', '', ['style' => 'margin-bottom:3ch;']);

$inputValues = [];
$fieldsAttributes = [
    ['name' => 'last10kTime', 'label' => 'Last 10k time: ', 'type' => 'text', 'domain' => UserData::GLOBAL_DOMAIN],
    ['name' => MarathonPlan::RACE_NAME_KEY, 'label' => 'Race Name: ', 'type' => 'text', 'domain' => MarathonPlan::DOMAIN],
    ['name' => MarathonPlan::RACE_DATE_KEY, 'label' => 'Race Date: ', 'type' => 'date', 'domain' => MarathonPlan::DOMAIN],
    ['name' => MarathonPlan::WEEKS_TO_TRAIN_KEY, 'label' => 'Weeks to Train: ', 'type' => 'number', 'min' => '12', 'max' => '100', 'domain' => MarathonPlan::DOMAIN],
    ['name' => MarathonPlan::WEEKS_TO_TAPER_KEY, 'label' => 'Weeks to Taper: ', 'type' => 'number', 'min' => '2', 'max' => '3', 'value'=>'2', 'domain' => MarathonPlan::DOMAIN],    
    ['name' => MarathonPlan::PRESENT_KM_KEY, 'label' => 'Present Km: ', 'type' => 'number', 'min' => '20', 'max' => '200', 'domain' => MarathonPlan::DOMAIN],
    ['name' => MarathonPlan::PEAK_WEEK_KM_KEY, 'label' => 'Peak Week Km: ', 'type' => 'number', 'min' => '40', 'max' => '300', 'domain' => MarathonPlan::DOMAIN]
];

foreach ($fieldsAttributes as $attributes) {

    $id = $attributes['name'];
    $domain = $attributes['domain'];
    unset($attributes['domain']);

    //$storedValue = UserData::get($domain, $id);

    $row = $table->makeChild('tr');
    $td = $row->makeChild('td');
    $td->makeChild('h3', $attributes['label']);
    $td = $row->makeChild('td');
    $input = $td->makeChild('input', '', $attributes);
    $inputs[$id] = $input;

    // POSTED/GOTTEN/STORED?
    $value = $input->value();
    $storedValue = UserData::get($domain, $id);
    if ($value) {
        UserData::set($domain, $id, $value);
    } else if ($storedValue) {
        $input->setValue($storedValue);
    }
    $inputValues[$id] = $input->value();
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  THE WARNING OF USE
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

$submit = Tag::make('input', '', ['type' => 'submit', 'name' => 'update', 'value' => 'update', 'onclick' => Tools::$saveScrollPosition, 'style' => 'margin-bottom:2ch;']);

$warningRead = 'marathonPlanConditionsOfUseHaveBeenRead';

if ($submit->value()) {
    UserData::set(MarathonPlan::DOMAIN, $warningRead, true);
} else {
    if (UserData::get(MarathonPlan::DOMAIN, $warningRead)) {
        $form->makeChild('p', 'You have read and agree to the conditions of use', ['class' => 'error']);
    } else {
        $form->makeChild('p', 'Warning', ['class' => 'error']);
        $form->makeChild('p', 'I am not a coach and this is my own planner. '
                . ' Should you use it you do so at your own risk.'
                . ' It is possible to enter values that will produce an unattainable plan.'
                . ' Training involves a risk of injury in pursuit of your goals.'
                . ' You should validate the proposed workouts with your coach.'
                . ' By clicking on the button below you accept these conditions of use.'
                . ' ', ['class' => 'error']);
    }
}


//$submit = $form->makeChild('input', '', ['type' => 'submit', 'name'=>'update', 'value' => 'update', 'style' => 'margin-bottom:2ch;']);
$form->addChild($submit);
////////////////////////////////////////////////////////////////////////////////////////////////
/// INSTANTIATE THE MARATHON PLAN OBJECT
////////////////////////////////////////////////////////////////////////////////////////////////


try{
    
$plan = null;
if ($inputValues['last10kTime'] &&
        $inputValues['marathonPlanWeeksToTrain'] &&
        $inputValues['marathonPlanWeeksToTaper'] &&
        $inputValues['marathonPlanStartingKilometrage'] &&
        $inputValues['marathonPlanPeakKilometrage'] &&
        $inputValues['marathonPlanRaceDate']) {

    $plan = new MarathonPlan(
            Tools::decimalMinutesFromString($inputValues['last10kTime']),
            intval($inputValues['marathonPlanWeeksToTrain']),          
            intval($inputValues['marathonPlanWeeksToTaper']),
            intval($inputValues['marathonPlanStartingKilometrage']),
            intval($inputValues['marathonPlanPeakKilometrage']),
            $inputValues['marathonPlanRaceDate']
    );

    $weekCursor = is_numeric($_SERVER['QUERY_STRING']) ? intval($_SERVER['QUERY_STRING']) : $plan->planWeekToday();
}


////////////////////////////////////////////////////////////////////////////////////////////////
/// DAILY SCHEDULE OF RUNNING WITHIN PARTICULAR WEEK
////////////////////////////////////////////////////////////////////////////////////////////////


    if ($plan) {

        $startDate = $plan->planWeekStartDate($weekCursor);

        if ($startDate) {

            $table3 = Tag::make("table", "", ["class" => "MarathonPlanSubTable fadeInXY"]);

            $totalDistance = 0;

            for ($date = $startDate; $date <= $startDate->modify('+6 days'); $date = $date->modify('+1 days')) {

                $workout = $plan->makeWorkoutForDate($date);

                // styles based on whether plan day is today or not

                $backgroundColor = $date == Tools::today() ? 'white' : 'lightgray';
                $throbbingTextClass = $date == Tools::today() ? 'throbbingText' : '';
                $workoutTypeBackgroundColor = $workout->typeColor();

                $tableRow = $table3->makeChild('tr', '', ['style' => "background-color:$backgroundColor;"]);

                $tableRow->makeChild('td', $date->format('D d'));
                $tableRow->makeChild('td', $workout->typeString(), ['class' => $throbbingTextClass, 'style' => "background-color:$workoutTypeBackgroundColor;"]);

                $tableRow->makeChild('td', $workout->toString());

                $tableRow->makeChild('td', $workout->distance() ? sprintf('%0.1f', $workout->distance()) : '');
                $tableRow->makeChild('td', $workout->duration() ? Tools::decimalMinutesToHMSstring($workout->duration()) : '');

                $totalDistance += $workout->distance();
            }
        }

////////////////////////////////////////////////////////////////////////////////////////////////
/// WEEKLY SCHEDULE OF RUNNING
////////////////////////////////////////////////////////////////////////////////////////////////


        $trainingArticle = Tag::make('article', '', ['style' => 'margin:5%;']);

        $trainingArticle->makeChild('div', '', ['id' => 'selectedWeek', 'style' => 'visibility:visible;']);

        $table2 = $form->makeChild("table", "", ["class" => "MarathonPlanTable"]);

        $titleRow = $table2->makeChild("tr", '', ['style' => 'color:white; text-align:left; ']);

        $titleRow->makeChild("th", "", ['colspan' => '2']);
        $titleRow->makeChild("th", "Mon");
        $titleRow->makeChild("th", "Tue");
        $titleRow->makeChild("th", "Wed");
        $titleRow->makeChild("th", "Thu");
        $titleRow->makeChild("th", "Fri");
        $titleRow->makeChild("th", "Sat");
        $titleRow->makeChild("th", "Sun");
        $titleRow->makeChild("th", "");

        $titleRow = $table2->makeChild("tr", '', ['style' => 'color:white; background-color:plum; text-align:left;']);

        $titleRow->makeChild("td", "Week");
        $titleRow->makeChild("td", "Starts");

        $titleRow->makeChild("td", "Base");
        $titleRow->makeChild("td", "Reps/Hill");
        $titleRow->makeChild("td", "Track");
        $titleRow->makeChild("td", "Base");
        $titleRow->makeChild("td", "Tempo/Fa");
        $titleRow->makeChild("td", "Rest");
        $titleRow->makeChild("td", "Long Run");
        $titleRow->makeChild("td", "Total");

        $startDate = $plan->startDate();

        /*
         * THE LOOP
         */

        for ($week = 0; $plan->planWeekStartDate($week) <= $plan->raceDate(); $week++) {


            /*
             * FORMATTING OF THE ROW COLORS
             */

            $col = 'black';
            $amp = 0.2;
            $ampRed = $amp;
            $ampGreen = $amp;
            $ampBlue = $amp;
            $red = 255 * (1.0 - $ampRed * (1.0 - $plan->hardnessOfPhase($week)));
            $blue = 255 * (1.0 - $ampBlue * $plan->hardnessOfPhase($week));
            $green = 255 * (1.0 - $ampGreen * $plan->hardnessOfPhase($week));
            $bcol = "rgba($red, $green, $blue, 255)";
            $fontSize = '20';

            if ($plan->peakWeek($week)) {
                $bcol = 'rgba(255,110,110,255)';
            } else if ($plan->raceWeek($week)) {
                $bcol = 'lightyellow';
            } else if ($plan->tapering($week)) {
                $bcol = 'rgba(100,200,255,255)';
            } else if ($week === $plan->planWeekToday()) {
                
            }
            $style = "font-weight:bold; font-size: " . $fontSize . "px; color: $col; background-color: $bcol;";
            $bookmarkTDstyle = $week === $plan->planWeekToday() ? 'font-size:2em; color: orchid; text-shadow: 2px 2px 3px gray;' : '';

            $onclick = Tools::$saveScrollPosition .
                    "
                document.getElementById('selectedWeek').innerHTML = '$week';
                window.location.search = '$week';
                ";

            $titleRow = $table2->makeChild("tr", '', ['style' => $style, 'id' => "week$week", 'onclick' => $onclick, 'onmouseover' => '', 'onmouseout' => '']);

            /*
             *  NOW THE ACTUAL TABLE ENTRIES
             */

            $td = $titleRow->makeChild('td', $week + 1, ['style' => $bookmarkTDstyle]);

            if ($week === $weekCursor) { // insert the subtable instead
                $titleRow->setAttribute('class', 'fadeInY', true);
                $td = $titleRow->makeChild('td', '', ['colspan' => '8', 'style' => "padding:10px 0px 10px 0px; font-size:$fontSize;"]);
                $td->addChild($table3);
            } else { // make the row of entries
                $titleRow->makeChild('td', $plan->planWeekStartDate($week)->format('d M'));

                $start = $plan->planWeekStartDate($week);
                $finish = $start->modify('+ 7 days');
                for ($date = $start; $date < $finish; $date = $date->modify('+1 days')) {

                    if ($date == $plan->startDate()) {
                        $titleRow->makeChild('td', 'START');
                    } else if ($date == $plan->raceDate()) {
                        $titleRow->makeChild('td', 'RACE');
                    } else if ($date > $plan->startDate() && $date < $plan->raceDate()) {
                        $workout = $plan->makeWorkoutForDate($date);
                        $distance = $workout->distance();
                        $td = $distance > 0 ? sprintf('%0.1f', $distance) : 'rest';
                        $titleRow->makeChild('td', $td);
                    } else {
                        $titleRow->makeChild('td', '');
                    }
                }
            }

            $titleRow->makeChild('td', $plan->raceWeek($week) ? '' : sprintf('%0.0f', $plan->actualWeeklyDistance($week)), ['style' => $bookmarkTDstyle]);

            //$startDate = $startDate->modify('+7 day');
        }

        $titleRow = $table2->makeChild("tr", '', ['style' => 'color:white; text-align:left; ']);

        $titleRow->makeChild("th", "", ['colspan' => '2']);
        $titleRow->makeChild("th", "Mon");
        $titleRow->makeChild("th", "Tue");
        $titleRow->makeChild("th", "Wed");
        $titleRow->makeChild("th", "Thu");
        $titleRow->makeChild("th", "Fri");
        $titleRow->makeChild("th", "Sat");
        $titleRow->makeChild("th", "Sun");
        $titleRow->makeChild("th", "");

        $titleRow = $table2->makeChild("tr");
        $titleRow->makeChild('td', 'Predicted marathon time is ' . Tools::decimalMinutesToHMSstring($plan->athlete()->predictedMarathonTime()), ['colspan' => '8']);

        $body->addChild($trainingArticle);

        $trainingArticle->makeChild('h2', 'The Garmin app or field can be downloaded here');
        $link = $trainingArticle->makeChild('a', '', ['href' => 'https://apps.garmin.com/en-US/apps/44d6772e-58e5-4060-bffe-348ceb6171da']);
        $link->makeChild('img', '', ['src' => '../Images/Di.png', 'atl' => 'Garmin', 'class' => 'GarminLinkImage']);

        $link2 = $trainingArticle->makeChild('a', '', ['href' => 'https://apps.garmin.com/fr-FR/apps/1a02ed96-74f0-4b48-874d-2f417759e70f']);
        $link2->makeChild('img', '', ['src' => '../Images/DiField.png', 'atl' => 'Garmin', 'class' => 'GarminLinkImage']);

        /*
         * WORKOUT FOR ZWIFT / GARMIN ETC
         */


        $zwiftParentDiv = $trainingArticle->makeChild('div', '', ['id' => 'zwiftParentDiv']);

        $zwiftParentDiv->makeChild('h2', $weekCursor === $plan->planWeekToday() ? 'Workouts this week:' : 'Workouts on Week ' . $weekCursor + 1 . ':');

        // ITERATE THROUGH THE DAYS OF THE WEEK DATES
        $fromDate = $plan->planWeekStartDate($weekCursor /* $plan->planWeekToday() */);
        $endDate = $fromDate->modify('+7 days');
        for ($date = $fromDate; $date < $endDate; $date = $date->modify('+1 days')) {

            $workout = $plan->makeWorkoutForDate($date);
            $div = $zwiftParentDiv->makeChild('div', '', ['class' => ($date == Tools::today()) ? 'selectedWorkoutCard' : 'workoutCard']);
            $heading = $date->format('D d M') . ' - ' . $workout->typeString();
            $div->makeChild('div', $heading, ['class' => 'workoutLabel', 'style' => 'background-color:' . $workout->typeColor() . '; margin:1ch 0ch;']);

            $div->makeChild('div', $workout->toString(), ['class' => 'center font-size-24px font-weight-bold']);

            if ($workout->type() != Workout::rest) {
                $div->addChild($workout->makeGraph());

                // the running workout
                $pre = $div->makeChild('pre', '', ['class' => 'hideWithoutSpace']);
                $zwiftRunningWorkout = $workout->toZwiftRunningWorkout($heading);
                $zwiftRunningWorkoutString = $zwiftRunningWorkout->toString();
                $zwiftRunningWorkout->setLiterality(true);
                $pre->addChild($zwiftRunningWorkout);
                $zwiftRunningClipButton = makeClippingButton($zwiftRunningWorkoutString, 'Copy as Zwift Running');

                // the cycling workout
                $pre = $div->makeChild('pre', '', ['class' => 'hideWithoutSpace']);
                $zwiftCyclingWorkout = $workout->toZwiftCyclingWorkout($heading);
                $zwiftCyclingWorkoutString = $zwiftCyclingWorkout->toString();
                $zwiftCyclingWorkout->setLiterality(true);
                $pre->addChild($zwiftCyclingWorkout);
                $zwiftCyclingClipButton = makeClippingButton($zwiftCyclingWorkoutString, 'Copy as Zwift Cycling');

                // the Garmin workout
                $dayOfWeekFormat = 'D';
                $dateFormat = 'd-m-y';
                $garminButtonName = 'garminButtonForDate_' . $date->format($dayOfWeekFormat);
                $garminButton = Tag::make('button', 'Select for Garmin', ['name' => $garminButtonName, 'value' => $date->format($dateFormat), 'form' => 'form', 'onclick' => Tools::$saveScrollPosition]);

                if ($garminButton->pressed()) {
                    $garminDate = DateTimeImmutable::createFromFormat($dateFormat, $garminButton->value());
                    $garminWorkout = $plan->makeWorkoutForDate($garminDate);
                    $nestedArrays = $garminWorkout->toPreJSON();
                    $slope = UserData::get(UserData::PACE_VS_POWER, 'slope');
                    $intercept = UserData::get(UserData::PACE_VS_POWER, 'intercept');
                    if ($slope != null && $intercept != null){
                        $nestedArrays['paceVsPower'] = ['slope'=>$slope, 'intercept'=>$intercept];
                    }
                    UserData::set('MarathonPlan', 'workoutForToday', json_encode($nestedArrays));
                    //Tools::delayedPopup('Saved. Workout will now be available to the Marathon Planning App', 1000);
                    $body->makeChild('div', 'This workout is now available to the Marathon Planning App', ['class'=>'transientWindow']);
                }


                $buttonsDiv = $div->makeChild('div', '', ['class' => 'rowOfElements']);
                $buttonsDiv->addChild($zwiftRunningClipButton);
                $buttonsDiv->addChild($zwiftCyclingClipButton);
                $buttonsDiv->addChild($garminButton);
            }
        }
    } else {
        $trainingArticle->makeChild('h2', 'Add data then update to see plan...');
    }
} catch (Excpetion $e) {
    $trainingArticle->makeChild('h2', 'Sorry but something glitched. It may be me but check the values you have entered...');
}

////////////////////////////////////////////////////////////////////////////////////////////////
// THE PLAN APPEARANCE IS CONDITIONAL UPON THE PRESENCE OF A PLAN OBJECT SO DONT ACCESS IT
// OUTSIDE THE ABOVE BRACES OR IT WILL GENERATE AN ERROR FROM NULL
////////////////////////////////////////////////////////////////////////////////////////////////



$body->addChild(makeFooter());

$html->echo();

exit();

