<?php

class Athlete {

    const MARATHON_ALLURE = MarathonPlan::MARATHON_DISTANCE;
    const HALF_MARATHON_ALLURE = self::MARATHON_ALLURE / 2;
    const LONG_RUN_ALLURE_FAST = 10 * self::MARATHON_ALLURE;
    const LONG_RUN_ALLURE_SLOW = 14 * self::MARATHON_ALLURE;
    const BASE_RUN_ALLURE = self::LONG_RUN_ALLURE_FAST;
    const RECOVERY_ALLURE = 20 * self::MARATHON_ALLURE;
    const WARMUP_ALLURE = self::LONG_RUN_ALLURE_SLOW;
    const COOLDOWN_ALLURE = self::LONG_RUN_ALLURE_SLOW;

    private float $reigelConstant = 1.06;
    private float $last10kTime;

    function __construct(float $last10kTime) {
        $this->last10kTime = $last10kTime;
    }

    function updateLast10kTime(float $time): void {
        $this->last10kTime = $time;
    }

    function last10kTime(): float {
        return $this->last10kTime;
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// PREDICTIONS FROM DISTANCE
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function predictedRaceTimeFromDistance(float $distance): float {
        return $distance ? $this->last10kTime * ($distance / 10.0) ** $this->reigelConstant : 0;
    }

    function predictedRaceSpeedFromDistance(float $distance): float {
        $raceTime = $this->predictedRaceTimeFromDistance($distance);
        return $distance && $raceTime ? $distance / $raceTime : 0;
    }

    function predictedRacePaceFromDistance($distance): float {
        $raceSpeed = $this->predictedRaceSpeedFromDistance($distance);
        return $distance && $raceSpeed ? 1.0 / $raceSpeed : 0;
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// PREDICTIONS FROM TIME
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function predictedRaceDistanceFromTime(float $raceTime): float {
        return $this->last10kTime ? 10.0 * ($raceTime / $this->last10kTime) ** (1.0 / $this->reigelConstant) : 0;
    }

    function predictedRaceSpeedFromTime(float $raceTime): float {
        return $raceTime ? $this->predictedRaceDistanceFromTime($raceTime) / $raceTime : 0;
    }

    function predictedRacePaceFromTime(float $raceTime): float {
        $raceSpeed = $this->predictedRaceSpeedFromTime($raceTime);
        return $raceSpeed ? 1.0 / $raceSpeed : 0;
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// ZONES
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function zoneFromSpeed(float $speed):array {
        $Friel = [
            '0.00' => ['1', 'lightskyblue'],
            '0.78' => ['2', 'dodgerblue'],
            '0.88' => ['3', 'green'],
            '0.94' => ['4', 'orange'],
            '1.01' => ['5a', 'orangered'],
            '1.03' => ['5b', 'red'],
            '1.11' => ['5c', 'crimson']
        ];
        
        $thresholdMinutes = 30;
        $thresholdSpeed = $this->predictedRaceSpeedFromTime($thresholdMinutes);
        $ratio = $speed / $thresholdSpeed;

        $result = [];
        foreach ($Friel as $key => $value) {
            $threshold = floatval($key);
            if ($ratio > $threshold) {
                $result = $value;
            }
        }
        return $result;
    }
    
    function zoneFromPace(float $pace){
        return $pace ? $this->zoneFromSpeed(1 / $pace) : $this->zoneFromSpeed(1 / (1e-6 + $pace));
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// CONVENIENCE
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function recoverySpeed(): float {
        return $this->predictedRaceSpeedFromDistance(self::RECOVERY_ALLURE);
    }

    function recoveryPace(): float {
        return 1 / $this->recoverySpeed();
    }

    function recoveryAllure(): float {
        return RECOVERY_ALLURE;
    }

    function warmupSpeed(): float {
        return $this->predictedRaceSpeedFromDistance(self::WARMUP_ALLURE);
    }

    function warmupPace(): float {
        return 1 / $this->warmupSpeed();
    }

    function cooldownSpeed(): float {
        return $this->predictedRaceSpeedFromDistance(self::COOLDOWN_ALLURE);
    }

    function cooldownPace(): float {
        return 1 / $this->cooldownSpeed();
    }

    function mileSpeed(): float {
        return $this->predictedRaceSpeedFromDistance(Constants::MILE_LENGTH_IN_KILOMETERS);
    }

    function milePace(): float {
        return 1 / $this->mileSpeed();
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// THESE ARE HACKS AS TIME DISTANCE PAIRS NEEDED TO CALCULATE CRITICAL SPEED
/// THIS USES SINGLE VALUE AND REIDEL INSTEAD!! (-;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function criticalSpeed(): float {
// can be maintained for 25 mins - just above lactate threshold speed which can be maintained for 30 minutes
        return $this->predictedRaceSpeedFromTime(25);
    }

    function reserveDistance(): float {
        $raceDistance = 3;
        return $raceDistance - $this->criticalSpeed() * $this->predictedRaceTimeFromDistance($raceDistance);
    }

    function criticalPace(): float {
        return 1.0 / $this->criticalSpeed();
    }

    function criticalAllure() {
        return $this->criticalSpeed() * 20;
    }

    function thresholdSpeed(): float {
        return $this->predictedRaceSpeedFromTime(30);
    }

    function thresholdPace(): float {
        return 1.0 / $this->thresholdSpeed();
    }

    function thresholdAllure(): float {
        return $this->thresholdSpeed() * 30;
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// THESE ARE BASED ON DODGY CRITICAL SPEED CALCULATION
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function marathonEfficiency(): float {
        $Sc = $this->criticalSpeed();
        $a0 = 1.03071428571;
        $a1 = -0.00067142857;
        $e = ($a0 + sqrt(pow($a0, 2.0) + (4.0 * $a1 * MarathonPlan::MARATHON_DISTANCE) / $Sc)) / 2.0;
        return $e;
    }

    function predictedMarathonTime(): float {
        return MarathonPlan::MARATHON_DISTANCE / $this->predictedMarathonSpeed();
    }

    function predictedMarathonSpeed(): float {
        return $this->marathonEfficiency() * $this->criticalSpeed();
    }

    function predictedMarathonPace(): float {
        return 1.0 / $this->predictedMarathonSpeed();
    }
}
