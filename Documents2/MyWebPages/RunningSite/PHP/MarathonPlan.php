<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
///
/// MARATHON PLAN CLASS
///
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CONSTANT VALUES

include_once 'Athlete.php';

const TRAINING_PERIODICITY = 4;

class MarathonPlan {

    const DOMAIN = 'MarathonPlan';
    const RACE_NAME_KEY = 'marathonPlanRaceName';
    const RACE_DATE_KEY = 'marathonPlanRaceDate';
    const WEEKS_TO_TRAIN_KEY = 'marathonPlanWeeksToTrain';
    const WEEKS_TO_TAPER_KEY = 'marathonPlanWeeksToTaper';
    const PRESENT_KM_KEY = 'marathonPlanStartingKilometrage';
    const PEAK_WEEK_KM_KEY = 'marathonPlanPeakKilometrage';
    const WORKOUT_TYPE_BASE_1 = 0;
    const WORKOUT_TYPE_BASE_2 = 1;
    const WORKOUT_TYPE_REPS = 2;
    const WORKOUT_TYPE_TRACK = 3;
    const WORKOUT_TYPE_TEMPO = 4;
    const WORKOUT_TYPE_LONG = 5;
    const WORKOUT_TYPE_REST = 6;

    /*
     * POTENTIALLY CONFIGURABLE IN FUTURE
     */

    public static array $mapDayOfWeekToWorkoutType = [
        self::WORKOUT_TYPE_BASE_1,
        self::WORKOUT_TYPE_REPS,
        self::WORKOUT_TYPE_TRACK,
        self::WORKOUT_TYPE_BASE_2,
        self::WORKOUT_TYPE_TEMPO,
        self::WORKOUT_TYPE_REST,
        self::WORKOUT_TYPE_LONG
    ];
    public static array $workoutTypeStrings = [
        self::WORKOUT_TYPE_BASE_1 => 'Base Run 1',
        self::WORKOUT_TYPE_REPS => 'Reps / Hills',
        self::WORKOUT_TYPE_TRACK => 'Track',
        self::WORKOUT_TYPE_BASE_2 => 'Base Run 2',
        self::WORKOUT_TYPE_TEMPO => 'Tempo Run',
        self::WORKOUT_TYPE_REST => 'Rest',
        self::WORKOUT_TYPE_LONG => 'Long Run'
    ];

    const MARATHON_DISTANCE = 42.2;
    //const REPETITION_TIME = 25;
    //const INTERVAL_TIME = 30;
    // DISTANCE BREAKDOWN BY ACTIVITY

    const PERCENT_TEMPO = 17.5 / 100;
    const PERCENT_LONG_RUN = 27.5 / 100;

    private DateTimeImmutable $startDate;
    private DateTimeImmutable $startingWeekStartDate;
    private DateTimeImmutable $raceDate;
    private int $weeks; // weeks
    private int $taper = 2; // weeks;
    private float $startingWeeklyDistance;
    private float $peakWeeklyDistance;
    private Athlete $athlete;

    function __construct(float $last10kTime, int $weeks, int $weeksToTaper, float $startingWeeklyDistance, float $peakWeeklyDistance, string $raceDate) {

        $this->weeks = $weeks;
        $this->taper = $weeksToTaper;
        $this->raceDate = new DateTimeImmutable($raceDate);

        $this->startDate = $this->raceDate()->modify("-$weeks weeks"); // back n weeks then forward a day
        $weekDayAtStart = Tools::dayOfWeekFromDate($this->startDate); // zero indexed, monday is zero
        $this->startingWeekStartDate = $this->startDate()->modify("-$weekDayAtStart days"); // the date of the monday on or less than a week before the start date

        $this->startingWeeklyDistance = $startingWeeklyDistance;
        $this->peakWeeklyDistance = $peakWeeklyDistance;
        $this->athlete = new Athlete($last10kTime);
    }

    function athlete(): Athlete {
        return $this->athlete;
    }

    /*
     * Date mappings, from date to the workout time location both week, days and weekday;
     * And from the workout day to the current date
     * Back and forth
     */

    /////////////////////////////////////////
    // plan specific, from date to plan event
    /////////////////////////////////////////

    function planDayFromDate(DateTimeImmutable $date): int { // zero indexed
        return $this->startDate()->diff($date)->format('%r%a');
    }

    function planWeekFromDate(DateTimeImmutable $date): int {
        //return floor($date->diff($this->startingWeekStartDate)->format('%r%a') / 7);
        return floor($this->startingWeekStartDate->diff($date)->format('%r%a') / 7);
    }

    function makeWorkoutForDate(DateTimeImmutable $date): Workout {
        $week = $this->planWeekFromDate($date);
        $dayOfWeek = Tools::dayOfWeekFromDate($date);
        $workoutType = MarathonPlan::$mapDayOfWeekToWorkoutType[$dayOfWeek];
        $workout = $this->makeWorkout($workoutType, $week);
        $workout->setDoDate($date);
        return $workout;
    }

    /////////////////////////////////////////
    // plan specific, from plan event to date
    /////////////////////////////////////////

    function raceDate(): DateTimeImmutable {
        return $this->raceDate;
    }

    function startDate(): DateTimeImmutable {
        return $this->startDate;
    }

    function startingWeekStartDate(): DateTimeImmutable {
        return $this->startingWeekStartDate;
    }

    function startDateDayOfWeek(): int {
        return Tools::dayOfWeekFromDate($this->startDate());
    }

    function planWeekStartDate(int $week) {
        return $this->startingWeekStartDate->modify("+$week weeks");
    }

    function toString(?DateTimeImmutable $date = null): string {
        //$fmt = 'd / m / yy';
        $result = '<h3>';

        $result .= $this->dayOfWeekStringFromDate($date) . ' week is ' . $this->planWeekFromDate($date);

        return $result . '</h3>';
    }

    /////////////////////////////////////////////////
    // plan specific, from current date to plan event
    /////////////////////////////////////////////////

    function planDayToday(): int {
        return $this->planDayFromDate(Tools::now());
    }

    function planWeekToday(): int {
        return $this->planWeekFromDate(Tools::now());
    }

    //////////////////////////////////////////////////
    //////////////////////////////////////////////////
    //////////////////////////////////////////////////

    function peakWeek($week): bool {
        return $week == $this->weeks - $this->taper;
    }

    function raceWeek($week): bool {
        //return true;
        //return $week == $this->weeks - 1;
        return $week === $this->planWeekFromDate($this->raceDate());
    }

    function hardnessOfPhase(int $week): float {
        return 1.0 - sin($this->phase($week)) ** 2;
    }

    function tapering($week): bool {
        return $week > $this->weeks - $this->taper;
    }

    function taperingFactor(int $week): float {
        return $this->tapering($week) ? ($this->weeks - $week) / ($this->taper) : 1.0;
    }

    /*

      function buildingFactor(int $week): float {
      return $this->tapering($week) ? 1.0 : ($this->startingWeeklyDistance + $week * ($this->peakWeeklyDistance - $this->startingWeeklyDistance) / ($this->weeks - $this->taper)) / $this->peakWeeklyDistance;
      }
     * 
     */

    function buildingFactor(int $week): float {
        $buildingWeeks = $this->weeks - $this->taper;
        $distanceToBuild = $this->peakWeeklyDistance - $this->startingWeeklyDistance;
        $distance = $this->startingWeeklyDistance + $distanceToBuild * $week / $buildingWeeks;
        return $this->tapering($week) ? 1.0 : $distance / $this->peakWeeklyDistance;
    }

    function normalisedProgressWithinRamp(int $week): float {
        return $this->tapering($week) ? 1.0 : $week / ($this->weeks - $this->taper);
    }

    function phase(int $week) {
        $buildupWeeks = ($this->weeks - $this->taper);
        $cycles = round($buildupWeeks / TRAINING_PERIODICITY);
        $period = $buildupWeeks / $cycles;
        return pi() * $week / $period;
    }

    function perterbate(int $week): float {
        if ($this->tapering($week)) {
            return 1.0;
        } else {
            $amplitude = 0.25 * $this->buildingFactor($week);
            return 1.0 - $amplitude * sin($this->phase($week)) ** 2;
        }
    }

    function scaleFactor(int $week) {
        return $this->tapering($week) ? $this->taperingFactor($week) : $this->buildingFactor($week);
    }

    function targetWeeklyDistance(int $week): float {
        $distance = $this->peakWeeklyDistance * $this->scaleFactor($week) * $this->perterbate($week);
        return $distance;
    }

    function weeksToTrain(): int {
        return $this->weeks;
    }

////////////////////////////////////////////////////////////////////////////
/// DURATION OF EACH TYPE OF WORKOUT
////////////////////////////////////////////////////////////////////////////


    function share($value, $p, $min): array {
        $share1 = $p * $value;
        $share2 = (1.0 - $p) * $value;
        if ($share1 < $min) {
            $share2 += $share1;
            $share1 = 0.0;
        }
        $share2 = $share2 < $min ? 0 : $share2;
        return [$share1, $share2];
    }

///////////////////////////////////////////////////////////////////////////////////////////////
/// GET THE RESULTING WEEKLY MILEAGE RATHER THAN THE TARGET AFTER WORKOUT CREATION AND ROUNDING
///////////////////////////////////////////////////////////////////////////////////////////////


    function actualWeeklyDistance(int $week): float {
        $result = 0;
        foreach (MarathonPlan::$mapDayOfWeekToWorkoutType as $day => $type) {
            $result += $this->makeWorkout($type, $week)->distance();
        }
        return $result;
    }

////////////////////////////////////////////////////////////////////////////
/// CREATE THE WORKOUT
////////////////////////////////////////////////////////////////////////////


    function makeWorkout(int $type, int $week): Workout {

        $result = null;

        //200,300,400,500,
        //600,800,1000

        $intervalDistances = [0.2, 0.6, 0.3, 0.8, 0.4, 1.0, 0.5, 0.6, 0.2, 0.8, 0.3, 1.0, 0.4, 0.5];

        // 200, 300, 400, 
        // 500, 600

        $repetitionDistances = [0.2, 0.5, 0.3, 0.6, 0.4, 0.5, 0.2, 0.6, 0.3, 0.4];

        //$weekOfYear = $this->startDate()->format('W') + $week; // does not work, not sure why


        $weekOfYear = $this->startDate()->modify("+$week weeks")->format('W'); // think about this, the week of the year corresponding to the week parameter

        $weeksLeft = $this->weeks - $this->taper - $week;
        $weeksLeft = $weeksLeft >= 0 ? $weeksLeft : 0;

        $duration = 0.0;

        switch ($type) {
            case self::WORKOUT_TYPE_LONG:
                $duration = $this->raceWeek($week) ? 42.2 : self::PERCENT_LONG_RUN * $this->targetWeeklyDistance($week);
                $allure = $this->hardnessOfPhase($week) > 0.5 ? Athlete::LONG_RUN_ALLURE_SLOW : Athlete::LONG_RUN_ALLURE_FAST;
                $result = Workout::makeLongRun($duration, $this->athlete->last10kTime(), $allure);
                break;

            case self::WORKOUT_TYPE_TRACK:
                // track rotation is linked to weekOfYear so that whatever the plan the track session is the same
                $index = $week % sizeof($intervalDistances) >= 0 ? $week % sizeof($intervalDistances) : 0;
                $activeStepLength = $intervalDistances[$index];
                //$result = Workout::makeTrackWorkout($this->taperingFactor($week) * self::INTERVAL_TIME, $this->athlete->last10kTime(), $activeStepLength);
                $result = Workout::makeTrackWorkout($this->athlete->last10kTime(), $activeStepLength, $this->taperingFactor($week) * Workout::intervalDuration);
                break;

            case self::WORKOUT_TYPE_REPS:
                $index = $week % sizeof($repetitionDistances) >= 0 ? $week % sizeof($repetitionDistances) : 0;
                $activeStepLength = $repetitionDistances[$index];
                //$result = Workout::makeRepetitionWorkout($this->taperingFactor($week) * self::REPETITION_TIME, $this->athlete->last10kTime(), $activeStepLength);
                $result = Workout::makeRepetitionWorkout($this->athlete->last10kTime(), $activeStepLength, $this->taperingFactor($week) * Workout::repetitionDuration);
                break;

            case self::WORKOUT_TYPE_TEMPO:
                $duration = self::PERCENT_TEMPO * $this->targetWeeklyDistance($week);
                $reps = 1 + ($weeksLeft % 3);
                $easyToHard = Tools::interpolate(42.0, $this->normalisedProgressWithinRamp($week), 21.0);
                $justHard = 21;
                $allure = $this->hardnessOfPhase($week) > 0.5 ? $easyToHard : $justHard;
                $result = Workout::makeTempoRun($duration, $this->athlete->last10kTime(), $reps, $allure);
                break;

            case self::WORKOUT_TYPE_BASE_1:
                $base = (1.0 - self::PERCENT_LONG_RUN - self::PERCENT_TEMPO) * $this->targetWeeklyDistance($week) - $this->makeWorkout(self::WORKOUT_TYPE_TRACK, $week)->distance() - $this->makeWorkout(self::WORKOUT_TYPE_REPS, $week)->distance();
                list($duration, $discard) = $this->share($base, 0.4, 5.0);
                $allure = Athlete::BASE_RUN_ALLURE;
                $result = Workout::makeSimpleRun($duration, $this->athlete->last10kTime(), $allure);
                break;

            case self::WORKOUT_TYPE_BASE_2:
                $base = (1.0 - self::PERCENT_LONG_RUN - self::PERCENT_TEMPO) * $this->targetWeeklyDistance($week) - $this->makeWorkout(self::WORKOUT_TYPE_TRACK, $week)->distance() - $this->makeWorkout(self::WORKOUT_TYPE_REPS, $week)->distance();
                list($duration, $discard) = $this->share($base, 0.6, 5.0);
                $allure = Athlete::BASE_RUN_ALLURE;
                $result = Workout::makeSimpleRun($duration, $this->athlete->last10kTime(), $allure);
                break;

            case self::WORKOUT_TYPE_REST:
                $result = Workout::makeRest();
                break;

            default:
                throw new Exception("'" . $type . "' is not a valid type argument to method runningDistance(string $type, int $week)");
        }

        return $result;
    }
}

return;

/*

$raceDate = '05/05/2025';
$plan = new MarathonPlan(53, 11, 50, 110, $raceDate);

$endDate = DateTimeImmutable::createFromFormat('d/m/yy', $raceDate);

$startDate = $plan->startDate();

echo $plan->startDate()->format('d/m/yy');
echo '<br>';
echo $plan->startDateDayOfWeek();

for ($date = $startDate; $date <= $endDate; $date = $date->modify('+1 days')) {
    echo $plan->toString($date);
}
 * 
 */


