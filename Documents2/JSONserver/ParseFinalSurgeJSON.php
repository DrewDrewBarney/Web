<?php

// In this form parseFinalSurgeJSONstring returns a JSON described data structure not a JSON string !!


function parseFinalSurgeJSONstring($JSONstr) {  // accepts a string and returns a json described data structure
    $result = [];
    $JSON = json_decode($JSONstr, false);
    if ($JSON != null) {
        $result['workoutName'] = $JSON->workoutName;
        $result['sport'] = $JSON->sport;
        $result['workoutId'] = $JSON->workoutId;
    } else {
        $result['error'] = 'JSONstr supplied to parseFinalSurgeJSONstring did not parse successfully';
    }
    $result['steps'] = parseWorkoutSteps($JSON->steps);
    $result['overview'] = parseWorkoutStepsForOverview($JSON->steps);

    return $result;
}

// non-recursive entry function to access recursive function _f()
function parseWorkoutSteps($steps) {
    $context = '';
    $result = [];
    _parseWorkoutSteps($steps, $context, $result);
    return $result;
}

function _parseWorkoutSteps($steps, $context, &$result) {
    foreach ($steps as $step) {
        if ($step->type === 'WorkoutStep') {
            $result[] = parseStep($step, false, $context); // end of recursion
        } else if ($step->type === "WorkoutRampStep") {
            $result[] = parseStep($step, true, $context); // end of recursion
        } else if ($step->type === "WorkoutRepeatStep") {
            $repeats = $step->repeatValue === 0 ? 1 : $step->repeatValue;
            for ($i = 0; $i < $repeats; $i++) {
                $newContext = $i + 1 . ':' . $repeats;
                _parseWorkoutSteps($step->steps, $newContext, $result); // recursive call
            }
        }
    }
}

// non-recursive
function parseStep($phase, $isRamp, $context) {

    // turn Final Surge seconds to milliseconds for Garmin devices   
    if ($phase->durationType === 'TIME') {
        $durationValue = $phase->durationValue * 1000;
    } else {
        $durationValue = $phase->durationValue;
    }

    $result = [];

    // non native
    $result['context'] = $context; // the context in an iteration, such as sep 2 of 3
    
    $result['primaryTargetRampRange'] = null;

    // native
    $result['intensity'] = $phase->intensity;
    $result['durationType'] = $phase->durationType;
    $result['durationValue'] = $durationValue;
    $result['primaryTargetType'] = $phase->primaryTargetType;

    // if a simple step just assign the low and high target values, switching if needed (pace) and widening if needed
    // if a ramp step assign high and low the start target value, widen and calculate a ramp factor

    if ($isRamp) {
        $low = $phase->primaryTargetValueStart;
        $high = $low;
        $result['primaryTargetRampRange'] = $phase->primaryTargetValueFinish - $phase->primaryTargetValueStart;
    } else {
        // have to check for low high and high low values for target range and swap if needed (typically for pace)
        $low = min([$phase->primaryTargetValueLow, $phase->primaryTargetValueHigh]);
        $high = max([$phase->primaryTargetValueLow, $phase->primaryTargetValueHigh]);
    }

    // widen range from zero if needed
    if ($low === $high) {
        $low *= 0.9;
        $high *= 1.1;
    }

    $result['primaryTargetValueLow'] = $low;
    $result['primaryTargetValueHigh'] = $high;

    return $result;
}


/*
function parseRampStep($phase, $context) {
    // to be implemented

    if ($phase->durationType === 'TIME') {
        $durationValue = $phase->durationValue * 1000;
    } else {
        $durationValue = $phase->durationValue;
    }

    $result = [];
    
    $result['context'] = $context; // the context in an iteration, such as sep 2 of 3

    $result['intensity'] = $phase->intensity;

    $result['durationType'] = $phase->durationType;
    $result['durationValue'] = $durationValue;

    $result['primaryTargetType'] = $phase->primaryTargetType;

    // have to check for low high and high low values for target range and correct if needed
    $result['primaryTargetValueLow'] = min([$phase->primaryTargetValueLow, $phase->primaryTargetValueHigh]);
    $result['primaryTargetValueHigh'] = max([$phase->primaryTargetValueLow, $phase->primaryTargetValueHigh]);

    return $result;
}
 * 
 */



function parseWorkoutStepsForOverview($steps) {
    return _parseWorkoutStepsForOverview(0, $steps);
}

function _parseWorkoutStepsForOverview($order, $steps) {
    $result = [];
    foreach ($steps as $step) {
        if ($step->type === 'WorkoutStep') {
            $result[] = parseStepForOverview($order, $step); // recursion termination
        } else if ($step->type === "WorkoutRampStep") {
            $result[] = parseRampStepForOverview($order, $step); // recursion termination (ramp step not implemented )
        } else if ($step->type === "WorkoutRepeatStep") {
            $result[] = [$order, 'then ' . $step->repeatValue . 'x {'];
            $result = array_merge($result, _parseWorkoutStepsForOverview($order + 1, $step->steps));
            $result[] = [$order, '}'];
        } else {
            $result[] = [$order, 'unknow workout step type'];
        }
    }
    return $result;
}



function parseStepForOverview($order, $phase) {

    $duration = 'OPEN';

    switch ($phase->durationType) {
        case 'TIME':
            $duration = 'duration ' . secondsToDurationString($phase->durationValue);
            break;

        case 'DISTANCE':
            $duration = 'distance ' . metresToDurationString($phase->durationValue);
            break;

        default:
            $duration = "OPEN";
            break;
    }

    $low = min([$phase->primaryTargetValueLow, $phase->primaryTargetValueHigh]);
    $high = max([$phase->primaryTargetValueLow, $phase->primaryTargetValueHigh]);

    switch ($phase->primaryTargetType) {
        case 'PACE':
            $primaryTargetLow = 'pace low ' . metresPerSecondToPaceString($low);
            $primaryTargetHigh = 'pace high ' . metresPerSecondToPaceString($high);
            break;

        case 'POWER':
            $primaryTargetLow = 'power low ' . $low . ' W';
            $primaryTargetHigh = 'power high ' . $high . ' W';
            break;

        case 'HEART_RATE':
            $primaryTargetLow = 'hr low' . $low . ' bpm';
            $primaryTargetHigh = 'hr high' . $high . ' bpm';
            break;

        default:
            $primaryTargetLow = 'FREE';
            $primaryTargetHigh = 'FREE';
            break;
    }


    $result = $phase->intensity;
    $result .= "\n" . $duration;
    $result .= "\n" . $primaryTargetLow;
    $result .= "\n" . $primaryTargetHigh;

    return [$order, $result];
}



function parseRampStepForOverview($order, $phase) {
    // to be implemented
    
 
    $duration = '';

    switch ($phase->durationType) {
        case 'TIME':
            $duration = 'duration ' . secondsToDurationString($phase->durationValue);
            break;

        case 'DISTANCE':
            $duration = 'distance ' . metresToDurationString($phase->durationValue);
            break;
    }

    $low = $phase->primaryTargetValueStart;
    $high = $phase->primaryTargetValueFinish;

    switch ($phase->primaryTargetType) {
        case 'PACE':
            $primaryTargetLow = 'pace start ' . metresPerSecondToPaceString($low);
            $primaryTargetHigh = 'pace end ' . metresPerSecondToPaceString($high);
            break;

        case 'POWER':
            $primaryTargetLow = 'power start ' . $low . ' W';
            $primaryTargetHigh = 'power end ' . $high . ' W';
            break;

        case 'HEART_RATE':
            $primaryTargetLow = 'hr start' . $low . ' bpm';
            $primaryTargetHigh = 'hr end' . $high . ' bpm';
            break;

        default:
            $primaryTargetLow = '';
            $primaryTargetHigh = '';
            break;
    }


    //$result = $phase->intensity;
    $result = "RAMP STEP";
    $result .= "\n" . $duration;
    $result .= "\n" . $primaryTargetLow;
    $result .= "\n" . $primaryTargetHigh;

    return [$order, $result];
}

// Value Formatting Functions for Overview

function secondsToDurationString($seconds) {
    $t = round($seconds);
    if ($seconds < 3600) {
        return sprintf('%d:%02d', ($t / 60 % 60), $t % 60);
    } else {
        return sprintf('%d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }
}

function metresToDurationString($metres) {
    $d = round($metres);
    if ($d < 1000) {
        return sprintf('%d m', $d);
    } else {
        $d /= 1000;
        return sprintf('%0.2f km', $d);
    }
}

function metresPerSecondToPaceString($metresPerSecond) {
    return sprintf('%0.2f min/km', 1000 / ($metresPerSecond * 60));
}
