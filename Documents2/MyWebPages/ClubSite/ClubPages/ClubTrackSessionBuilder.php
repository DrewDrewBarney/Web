<!DOCTYPE HTML>
<?php
session_start();
include_once '../../Common/PHP/roots.php';
include_once '../ClubPHP/clubAll.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

list($html, $head, $body) = makePage('Track Session Builder');
$form = $body->makeChild('form', '', ['id' => 'form']);
$body->setAttribute('onload', Tools::$restoreScrollPosition);

$form->addChild(makeTopBar(), ['class' => 'dontPrint'], true)->addChildren([
    makePageTitle("Athletic Club Angerien"),
    makeMenu(MENU_CLUB_HOME, 'ClubTrackSessionBuilder.php'),
    makePageTitle('Track Session Builder')
]);

// image banner
include_once '../ClubPHP/ClubTools.php';
$form->addChild(makeClubBanner("../ClubImages/Stopwatch.png"), ['class' => 'dontPrint'], true);

$schemaArticle = $form->makeChild('article', '', ['class' => 'dontPrint']);

$schemaArticle->makeChild('h2', 'Raisonnement');
$schemaArticle->makeChild('p', "Les coureurs d'un club ont généralement des niveaux et des expériences variés. Il est donc nécessaire d'ajuster le nombre de répétitions pour s'assurer que tous travaillent aussi dur les uns que les autres pendant à peu près le même temps.. "
);

/*
 * 
 * the exemplaire
 * 
 */

$schemaArticle->makeChild('h3', 'Session de modèle');
$schemaArticle->makeChild('p', '(hors échauffement et retour au calme)');

/*
 * the number of phases of training
 */


$numberOfPhasesInput = $schemaArticle->makeChild('td')->makeChild('input', ' nombre de phases',
        ['type' => 'number', 'name' => "numberOfPhases", 'value' => '1', 'min' => '1', 'max' => '40',
            'onchange' => Tools::$saveScrollPosition . "document.getElementById('form').submit();"
        ]);
$numberOfPhases = $numberOfPhasesInput->value() ? $numberOfPhasesInput->value() : 1;

$schemaArticle->addChild(makeSpace('2ch'));

$table = $schemaArticle->makeChild('table', '', ['class' => 'tableStyleTrackIntervalsForm']);

$titleRow = $table->makeChild('tr');
$titleRow->makeChild('th', 'Répétitions');
$titleRow->makeChild('th', 'Distance Active');
$titleRow->makeChild('th', 'Allure Specifique');
$titleRow->makeChild('th', 'Récupérer la distance');

/*
 * build the template session
 */

$templateSession = new ClubTrackSession();

for ($i = 0; $i < $numberOfPhases; $i++) {
    $row = $table->makeChild('tr');
    $reps = $row->makeChild('td')->makeChild('input', '', ['type' => 'number', 'name' => "reps_$i", 'min' => '1', 'max' => '50', 'value' => '1']);
    $activeDistance = $row->makeChild('td')->makeChild('input', ' m', ['type' => 'number', 'name' => "activeDistance_$i", 'min' => '100', 'max' => '2000', 'step' => '100', 'value' => '400']);
    $allure = $row->makeChild('td')->makeChild('input', ' as', ['type' => 'number', 'name' => "allure_$i", 'min' => '1', 'max' => '42', 'value' => '10']);
    $recoveryDistance = $row->makeChild('td')->makeChild('input', ' m', ['type' => 'number', 'name' => "recoveryDistance_$i", 'min' => '100', 'max' => '2000', 'step' => '100', 'value' => '200']);

    if ($reps->intVal()) {
        $templateSession->addPhase(
                new ClubTrackSessionPhase($reps->intVal(), $activeDistance->floatVal() / 1000, $allure->intVal(), $recoveryDistance->floatVal() / 1000)
        );
    }
}

/*
 * NUMBER OF REPEATS OF THE BASIC WORKOUT PATTERN
 */
$schemaArticle->addChild(makeSpace('2ch'));
$numberOfRepeats = $schemaArticle->makeChild('td')->makeChild('input', ' nombre de repetitions de si dessus',
        ['type' => 'number', 'name' => "numberOfReps", 'value' => '1', 'min' => '1', 'max' => '5',
        /* 'onchange' => "document.getElementById('form').submit();" */
        ]);

if ($numberOfRepeats->intVal()) {
    $templateSession->grow($numberOfRepeats->intVal());
}

/*
 * the target duration
 */

$schemaArticle->makeChild('h3', 'Durée cible pour les intervalles');
$schemaArticle->makeChild('p', '(hors échauffement et retour au calme)');
$duration = $schemaArticle->makeChild('td')->makeChild('input', ' minutes', ['type' => 'number', 'name' => "duration", 'value' => '30', 'min' => '20', 'max' => '60']);

/*
 * what to change, reps, distance or a bit of both, THE FOCUS
 */

$schemaArticle->makeChild('h3', "Programme d'entraînement généré");
$schemaArticle->makeChild('p', "Type d'entraînement dérivé, en modifiant le nombre de répétitions, la distance de répétition ou un mélange des deux");

/*
  $selectScaledWorkoutFocus = $form->makeChild('select', '', ['name' => 'focus']);
  foreach (ClubTrackSession::$focusStrings as $optionLabel => $associatedValue) {
  $option = $selectScaledWorkoutFocus->makeChild('option', $optionLabel, ['name' => $optionLabel]);
  if ($optionLabel === $selectScaledWorkoutFocus->value()) {
  $option->setAttributes(['selected' => 'selected']);
  }
  }
 * 
 */

$selectScaledWorkoutFocus = makeSelect(ClubTrackSession::CAPTION_VALUES, ClubTrackSession::VARY_NUMBERS);
$schemaArticle->addChild($selectScaledWorkoutFocus);

$schemaArticle->makeChild('div', '', ['style' => 'display:flex; height: 5em;']);

/*
 * if create button pressed then start building all the sessions
 * id pring button pressed then do the same but select that which is displayed and activate print menu via javascript
 */

$createButton = $schemaArticle->makeChild('button', 'créer et visualiser', ['name' => 'create', 'value' => 'create', 'onclick' => Tools::$saveScrollPosition]);
$printButton = Tag::make('button', 'imprimer', ['type' => 'submit', 'form' => 'form', 'name' => 'print', 'value' => 'print', 'style' => 'margin:2ch;']);
$garminButtonCaption = UserManagement::loggedIn() ? 'Envoyer à montre Garmin' : 'Login à faire envoyer à montre Garmin';
$garminButton = Tag::make('button', $garminButtonCaption, ['type' => 'submit', 'form' => 'form', 'name' => 'garmin', 'value' => 'garmin', 'onClick' => Tools::$saveScrollPosition, 'style' => 'margin:2ch;']);
$last10kTimeInput = Tag::make('input', '', ['name' => 'last10kTime', 'id' => 'last10kTime', 'value' => UserData::get('', 'last10kTime')]);
$last10kTimeInput->makeChild('label', 'last 10k time', ['id' => 'last10kTime', 'class' => 'dontPrint'], true);

//$form->addChild($templateSession->makeDisplay());

$rangeOfLast10kTimes = range(30, 70);

/*
 * NOW CRACK ON AND DISPLAY THE WORKOUT
 */

//$html->echo();exit;


if (($createButton->pressed() || $printButton->pressed() || $garminButton->pressed()) && $templateSession->distance()) {


    $trainingArticle = $form->makeChild('article', '', ['style' => 'margin:0ch;']);

    //$graph = $templateSession->makeDisplay();
    //$graph->setAttributes(['class' => 'dontPrint'], true);

    $trainingArticle->addChild($templateSession->makeDisplay(), ['class' => 'dontPrint'], true);

    $trainingArticle->addChild(makeSpace('3ch'));

    $trainingArticle->makeChild('h3', "Ce sont les entraînements équivalents les plus proches basés sur des valeurs raisonnablement arrondies", ['style' => 'margin:1ch;']);

    $trainingTable = $trainingArticle->makeChild('table', '', ['class' => 'tableStyleTrackIntervals']);

    $buckets = []; // an array for a load of buckets of sessions

    foreach ($rangeOfLast10kTimes as $last10kTime) {
        $focus = $selectScaledWorkoutFocus->intVal();
        $scaledSession = $templateSession->makeScaled($duration->floatVal(), $last10kTime, $focus);
        $key = $scaledSession->toString('<br>');
        if (!isset($buckets[$key])) {
            $buckets[$key] = new ClubTrackSessionsBucket();
        }
        $buckets[$key]->add($scaledSession);
    }


    $headingsStyle = '';
    $row = $trainingTable->makeChild('tr');
    $row->makeChild('th', 'Dernière 10k');
    $row->makeChild('th', 'Structure');
    $row->makeChild('th', 'Cible 100m');
    $row->makeChild('th', 'Cible Tour');
    $row->makeChild('th', 'Cible 1km');

    $odd = false;
    $itemsStyle = 'white-space:nowrap;';

    foreach ($buckets as $key => $scaledSessionsBucket) {
        $alternateLineStyle = $odd ? 'background-color:rgb(245,230,245);' : '';
        $odd = !$odd;
        $row = $trainingTable->makeChild('tr', '', ['style' => $alternateLineStyle]);

        $row->makeChild('td', $scaledSessionsBucket->lowToHigh10kTimeString(), ['style' => $itemsStyle]);
        $row->makeChild('td', $key, ['style' => $itemsStyle]);

        $row->makeChild('td', $scaledSessionsBucket->paces(0.1), ['style' => $itemsStyle]);
        $row->makeChild('td', $scaledSessionsBucket->paces(0.4), ['style' => $itemsStyle]);
        $row->makeChild('td', $scaledSessionsBucket->paces(1.0), ['style' => $itemsStyle]);
    }


    if (UserManagement::loggedIn()) {

        $form->addChild($printButton, ['class' => 'dontPrint'], true);

        $personalArticle = $form->makeChild('article');
        $personalArticle->addChild($last10kTimeInput, ['class' => 'dontPrint'], true);

        if ($last10kTimeInput->value()) {
            UserData::set('', 'last10kTime', $last10kTimeInput->value());

            $personalArticle->makeChild('h3', 'À Toi');

            $last10kTime = Tools::decimalMinutesFromString($last10kTimeInput->value());
            $athlete = new Athlete($last10kTime);
            $personalSession = $templateSession->makeScaled($duration->floatVal(), $last10kTime, $selectScaledWorkoutFocus->value());

            $personalTable = $personalArticle->makeChild('table', '', ['class' => 'tableStyleTrackIntervals']);
            $row = $personalTable->makeChild('tr');
            $row->makeChild('th', 'Phase');
            $row->makeChild('th', 'Cible 100m');
            $row->makeChild('th', 'Cible Tour');
            $row->makeChild('th', 'Cible 1km');

            foreach ($personalSession->phases() as $phase) {
                $tr = $personalTable->makeChild('tr');
                $tr->makeChild('td', $phase->toString());
                $pace = $phase->pace($athlete);
                $tr->makeChild('td', Tools::decimalMinutesToHMSstring($pace * 0.1));
                $tr->makeChild('td', Tools::decimalMinutesToHMSstring($pace * 0.4));
                $tr->makeChild('td', Tools::decimalMinutesToHMSstring($pace * 1));
            }

            $personalArticle->addChild($garminButton, ['class' => 'dontPrint'], true);

            if ($garminButton->pressed()) {
                $preJSON = $personalSession->toPreJSON($athlete);
                $JSONstring = json_encode($preJSON);
                UserData::set('MarathonPlan', 'workoutForToday', $JSONstring);
                $body->makeChild('div', "Cet entraînement est désormais disponible dans l'application Garmin", ['class' => 'transientWindow']);
            }
        } else {
            $personalArticle->makeChild('h6', 'Ajoutez votre dernier temps de course de 10 km pour une prescription personnalisée');
        }
    } else {
        $personalArticle = $form->makeChild('article');

        $personalArticle->makeChild('h3', 'Connectez-vous pour obtenir une ordonnance personnalisée');
    }
}

$form->addChild(makeFooter(), ['class' => 'dontPrint'], true);

if ($printButton->pressed()) {
    $form->makeChild('script', 'window.print()');
}

$html->echo();

//print_r($_GET);

