<?php

// tells us this is JSON
header('Content-Type: application/json');
// switch on display of errors
ini_set('display_errors', 1);
// stop html formatted errors from PHP as they are a pain to read in JSON return
ini_set('html_errors', false);


define("SECONDS_IN_A_DAY", 24 * 60 * 60);
define("BASE_URI", "https://what-is.todaysplan.com.au/");
define("USER_INFO_URI", BASE_URI . "rest/users/me");
define("USER_ACTIVITIES_URI", BASE_URI . "rest/users/activities/search/0/1");

function requestWorkoutData($token, $metricType, $workoutID) {

    $WorkoutDataURI = BASE_URI . "rest/workouts/get/" . $metricType . "/ccpd/" . $workoutID;

    $headers = [
        "Content-Type:application/json",
        "Authorization:Bearer " . $token
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $WorkoutDataURI);
    curl_setopt($ch, CURLOPT_POST, 0); // uses GET
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $JSONstring = curl_exec($ch);
    $JSON = null;

    if ($JSONstring === false) {
        $JSON = ['error' => 'call to requestWorkoutData failed'];
    } else if ($JSONstring === null) {
        $JSON = ['error' => 'call to requestWorkoutData returned nothing'];
    } else {
        $JSON = json_decode($JSONstring, true);
    }
    return $JSON;
}

function requestUserActivitiesOverview($token, $ID) {

    // uses POST

    $startOfDay = SECONDS_IN_A_DAY * intdiv(time(), SECONDS_IN_A_DAY);
    $from = $startOfDay;
    $to = $startOfDay + 7 * SECONDS_IN_A_DAY;

    // Today's plan uses Unix Milliseconds!
    $from *= 1000;
    $to *= 1000;

    // Then passes as strings in a decimal format as parameters to call

    $from = sprintf("%0.0f", $from);
    $to = sprintf("%0.0f", $to);

    $parameters = [
        'criteria' => [
            'meta' => [
                'fields' => 'scheduled.simple,scheduled.ts,workout,state,reason',
                'isNull' => 'fileId',
                'excludeWorkouts' => 'rest,event',
                'sports' => 'ride,run,swim'
            ],
            'user' => [
                'id' => $ID
            ],
            'fromTs' => $from,
            'toTs' => $to
        ],
        'opts' => 5
    ];

    $headers = [
        "Content-Type:application/json",
        "Authorization:Bearer " . $token
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, USER_ACTIVITIES_URI);
    curl_setopt($ch, CURLOPT_POST, 1); // uses POST
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $JSONstring = curl_exec($ch);
    $JSON = ['errror'=>'unknown error in call to requestUserActivities'];

    if ($JSONstring === false) {
        $JSON = ['error' => 'call to requestUserActivities failed'];
    } else if ($JSONstring === null) {
        $JSON = ['error' => 'call to requestUserActivities returned nothing'];
    } else {
        $JSON = json_decode($JSONstring, true);
        
        //return ['type'=>gettype($JSON), 'JSONstr'=>$JSONstring];
        
        $metricType = 'power';

        $workoutCount = $JSON['cnt'];
        $result = $JSON['result'];
        $workouts = $result['results']; // an array of workout pointers

        if ($workoutCount > 0) {
            if (count($workouts) > 0) {
                foreach ($workouts as $workout) {
                    $workoutID = $workout['workoutId'];
                    //$ts = $workout['ts'];  not used
                    $JSON = requestWorkoutData($token, $metricType, $workoutID);
               }
            }
        }

    }
    return $JSON;
}


function getUserInformation($token) {

    // uses GET 

    $headers = [
        "Content-Type:application/json",
        "Authorization:Bearer " . $token
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0); // uses GET
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, USER_INFO_URI);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $JSONstring = curl_exec($ch);
    $JSON = null;

    if ($JSONstring === false) {
        $JSON = ['error' => 'call to getUserInformation failed'];
    } else if ($JSONstring === null) {
        $JSON = ['error' => 'call to getUserInformation returned nothing'];
    } else {
        $JSON = json_decode($JSONstring, true);
        $ID = $JSON['id'];
        $JSON = requestUserActivitiesOverview($token, $ID);
    }

    return $JSON;
}

function main() {

    $headersIn = apache_request_headers();

    if (key_exists("Token", $headersIn)) {
        $token = $headersIn["Token"];
        $result = getUserInformation($token);
    } else {
        $result = ["error" => "no bearer token in header supplied to proxy server request"];
    }

    return json_encode($result);
}

echo main();

