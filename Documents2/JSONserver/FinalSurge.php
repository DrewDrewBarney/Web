<?php

// tells us this is JSON
header('Content-Type: application/json');
// switch on display of errors
ini_set('display_errors', 1);
// stop html formatted errors from PHP as they are a pain to read in JSON return
ini_set('html_errors', false);
// set up global exception handler
set_error_handler('exceptions_error_handler');
// include parsing library
include_once 'ParseFinalSurgeJSON.php';
include_once 'LoggingFunctions.php';
include_once 'ToolBox.php';


define("CLIENT_ID", "7C1A85FB-6CD9-4A2A-97DA-B8F7A505600B");
define("CLIENT_SECRET", "FR2MXWH6RwJUxBd3KLDwRGaBerDQwuE9CeWfshC36Yh7ezsCaHgeLcZ9bA8uC7Qe");
define("BASE_URI", "https://log.finalsurge.com/");
define("AUTHORIZE_URI", BASE_URI . "oauth/authorize");
define("TOKEN_URI", BASE_URI . "oauth/token");
define("TOKEN2_URI", BASE_URI . "API/v1/LoginToken");
define("WORKOUTS_URI", BASE_URI . "API/v1/UpcomingWorkouts");
define("REQUEST_CONTENT_TYPE_JSON", 1);

function getStructuredWorkout($token, $url) {

    $headers = [
        "Content-Type:application/json",
        "client-id:" . CLIENT_ID,
        "Authorization:Bearer " . $token
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $JSONstr = curl_exec($ch);

    if ($JSONstr === false) {
        return ['error' => 'request to UpcomingWorkouts failed'];
    } else if ($JSONstr === null) {
        return ['error' => 'nothing returned from UpcomingWorkouts'];
    } else {
        //appendLog($JSONstr);
        return parseFinalSurgeJSONstring($JSONstr); // return data not string
    }
}

/* Function which makes the first request to UpcomingWorkouts and obtains
 * the URL to a structured workout if present
 */

function getJSONfsV1($token, $workout) {
    $structuredWorkoutURLs = $workout["StructuredWorkoutURLs"];
    $result = ['error' => "structuredWorkoutURLs is null"];
    if ($structuredWorkoutURLs !== null) {
        $json_fs_v1 = $structuredWorkoutURLs["json_fs_v1"];
        $result = ['error' => "json v1 structured workout is null"];
        if ($json_fs_v1 !== null) {
            $result = getStructuredWorkout($token, $json_fs_v1);
        }
    }

    return $result; // return data not string
}

function getWorkouts($token) {
    // curl request - get the workout URLs

    $headers = [
        "Content-Type:application/json",
        "client-id:" . CLIENT_ID,
        "Authorization:Bearer " . $token
    ];


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, WORKOUTS_URI . '?NumWorkouts=4');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $JSONstring = curl_exec($ch);

    $result = ['error' => 'no workouts retrieved from UpcomingWorkouts'];

    if ($JSONstring === false) {
        $result = ['error' => curl_error($ch)];
    } else {

        $JSON = json_decode($JSONstring, true);
        $result = ['error' => "null JSON return from UpcomingWorkouts"];
        if ($JSON !== null) {
            $result = ['error' => "no Workouts key found in JSON return from UpcomingWorkouts"];
            if (key_exists("Workouts", $JSON)) {
                $workouts = $JSON["Workouts"];
                $result = ['error' => "Workouts value is null in JSON return from UpcomingWorkouts -> " . json_encode($workouts)];
                if ($workouts !== null) {

                    $result = [];

                    foreach ($workouts as $workout) {

                        // the enclosing workout information
                        $workoutDate = substr($workout['WorkoutDate'], 0, 10);
                        $workoutTitle = $workout['WorkoutTitle'];
                        $workoutDescription = $workout['WorkoutDescription'];

                        // the contained structured workout information
                        $structuredWorkoutData = getJSONfsV1($token, $workout);

                        $structuredWorkoutData['workoutDate'] = $workoutDate;

                        $workoutId = safelyIndex('workoutId', $structuredWorkoutData); 
                        
                        if ($workoutId) {
                            $sport = $structuredWorkoutData['sport'];

                            // build a key from both
                            $key = $workoutId;

                            // add structured workout data referenced by the key
                            $result[$key] = $structuredWorkoutData;
                        }
                    }
                }
            }
        }
    }

    curl_close($ch);

    return $result;
}

function mainFinalSurge() {

    appendLog('Downloading from Final Surge');

    $headersIn = apache_request_headers();

    if (key_exists("Token", $headersIn)) {
        $token = $headersIn["Token"];
        $result = getWorkouts($token);
    } else {
        $result = ["error" => "no bearer token"];
    }

    return json_encode($result);
}

// global error handling

function exceptions_error_handler($severity, $message, $filename, $lineno) {
    //throw new ErrorException($message, 0, $severity, $filename, $lineno);
    $fullMessage = $filename . ' ' . $lineno . ' ' . $message;
    appendLog($fullMessage);
    mail('shardlow.a@gmail.com', 'mainFinalSurge.php Error', $fullMessage); // mail may not send during exception state
    $result = ["error" => $fullMessage];
    echo json_encode($result);
}

// main entry point and exit echoing

echo mainFinalSurge();


