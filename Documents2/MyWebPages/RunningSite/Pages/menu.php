<?php

define('MENU_HOME',
        [
            'Home' => 'home.php',
            'Underlying Concepts' => 'basis.php',
            'Tools' => 'tools.php',
            'About the Author' => 'aboutMe.php',
            'Log in / out' => 'login.php'
        ]
);

define('MENU_BASIS',
        [
            'Physiology' => 'physiology.php',
            'Intensity Measures' => 'metrics.php',
            'Training Load & Performance' => 'trainingLoad.php'
        ]
);

define('MENU_TOOLS_LOGGED_IN',
        [
            'Interval and Repetition Paces' => 'intervalPaces.php',
            'Race Predictor'  =>  'racePredictor.php',
            'Critical Pace Calculator'  =>  'criticalPace.php',
            'Analyse Data'  =>  'dataProcessingHome.php',
            'Marathon Planner'  =>  'MarathonPlan.php',
            'Zwift Workout Builder' => 'Zwift.php',
            //'Track Session Builder' => 'ClubTrackSessionBuilder.php'
        ]
);

define('MENU_TOOLS_LOGGED_OUT',
        [
            'Log in to use Tools' => 'login.php',
        ]
);

