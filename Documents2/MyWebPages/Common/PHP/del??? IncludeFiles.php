<?php

function includeFiles(string $PHP_path = '') {
    

    include_once $PHP_path . 'menu.php';
    include_once $PHP_path . 'Constants.php';
    include_once $PHP_path . 'SimpleTable.php';
    include_once $PHP_path . 'Table.php';
    include_once $PHP_path . 'Athlete.php';
    include_once $PHP_path . 'ZwiftAthlete.php';
    include_once $PHP_path . 'ZwiftWorkout.php';

    include_once $PHP_path . 'MarathonPlan.php';

    include_once $PHP_path . 'Workout.php';
    include_once $PHP_path . 'dom.php';
    include_once $PHP_path . 'domCon.php';
    include_once $PHP_path . 'Tools.php';
    include_once $PHP_path . 'riegel.php';
    include_once $PHP_path . 'fitnessFileProcessing.php';
    include_once $PHP_path . 'statistics.php';
    include_once $PHP_path . 'GoogleChart.php';
    include_once $PHP_path . 'SimpleTable.php';
    include_once $PHP_path . 'CheckValue.php';
    include_once $PHP_path . 'Field.php';
    include_once $PHP_path . 'Database.php';
    include_once $PHP_path . 'RunningDatabase.php';
    include_once $PHP_path . 'UserManagement.php';
    include_once $PHP_path . 'UserData.php';
    include_once $PHP_path . 'inlineStylesForEmails.php';
}
