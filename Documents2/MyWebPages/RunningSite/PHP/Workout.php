<?php

//include_once 'ZwiftWorkout.php';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
///
/// WORKOUT CLASSES
///
//////////////////////////////////////////////////////////////////////////////////////////////////////////////



class Workout {

    const rest = 0;
    const base = 1;
    const reps = 2;
    const track = 3;
    const tempo = 4;
    const long = 5;
    const repetitionDuration = 25;
    const intervalDuration = 30;
    const easy = 3;
    const normal = 2.5;
    const hard = 2;
    const typeAttributes = [
        self::rest => ['caption' => 'Rest', 'color' => 'white'],
        self::base => ['caption' => 'Base', 'color' => 'lightskyblue'],
        self::reps => ['caption' => 'Reps/Hills', 'color' => 'violet'],
        self::track => ['caption' => 'Track', 'color' => 'red'],
        self::tempo => ['caption' => 'Tempo', 'color' => 'lime'],
        self::long => ['caption' => 'Long', 'color' => 'palegoldenrod'],
    ];

    protected Athlete $athlete;
    protected int $type = 0;
    protected ?DateTimeImmutable $doDate = null;
    protected float $cooldownDistance = 0;
    protected float $warmupDistance = 0;
    protected float $repetitionDistance = 0;
    protected float $periodicTrainingTime = 0;
    protected float $activeDistance = 0;
    protected float $activePace = 0;
    protected float $recoveryDistance = 0;
    protected int $repetitions = 0;
    protected float $totalTime = 0;

    function __construct(float $last10kTime) {
        $this->athlete = new Athlete($last10kTime);
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// INTERVALs AND REPETITIONs 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    static function makeTrackWorkout(float $last10kTime, float $oneIntervalDistance, float $periodicTrainingDuration = self::intervalDuration, float $severity = self::normal): Workout {
        $weights = [2, 0.5];
        $result = self::makePeriodicWorkout($weights, $periodicTrainingDuration, $last10kTime, $oneIntervalDistance, $severity);
        $result->type = self::track;
        return $result;
    }

    static function makeRepetitionWorkout(float $last10kTime, float $oneIntervalDistance, float $periodicTrainingDuration = self::repetitionDuration, float $severity = self::normal): Workout {
        $weights = [1 / 2, 0.5];
        $result = self::makePeriodicWorkout($weights, $periodicTrainingDuration, $last10kTime, $oneIntervalDistance, $severity);
        $result->type = self::reps;
        return $result;
    }

    static function makePeriodicWorkout(array $weights, $periodicTrainingDuration, float $last10kTime, float $oneIntervalDistance, $severity = self::normal): Workout {

        list($activeWeight, $repsPower) = $weights;
        $result = new Workout($last10kTime);

        $warmupTime = 15;
        $cooldownTime = 10;

        // known variables and calculated from known
        $activeTime = $periodicTrainingDuration * $activeWeight / (1 + $activeWeight);
        $recoveryTime = $periodicTrainingDuration * 1 / (1 + $activeWeight);

        // initial guess of $active distance
        $activeDistance = $result->athlete->predictedRaceDistanceFromTime($activeTime);
        $recoveryDistance = $result->athlete->recoverySpeed() * $recoveryTime;

        // initial guess of reps
        $reps = $activeDistance / $oneIntervalDistance;

        // iterate 
        foreach (range(1, 10) as $i) {
            $activeSpeed = $result->athlete->predictedRaceSpeedFromDistance($severity * $oneIntervalDistance * $reps ** (1.0 - $repsPower));
            $activeDistance = $activeTime * $activeSpeed;
            $reps = $activeDistance / $oneIntervalDistance;
        }

        $reps = (int)round($reps);
        $activeDistance = $reps * $oneIntervalDistance;
        $oneRecoveryDistance = $reps ? round($recoveryDistance / $reps, 1) : $recoveryDistance; // to nearest 0.1 k or 100m

        $activePace = $activeSpeed ? 1 / $activeSpeed : 0;
        $recoveryPace = 1 / $result->athlete->recoverySpeed();

        $distance = $reps * ($oneRecoveryDistance + $oneIntervalDistance);

        $result->repetitionDistance = $distance;
        $result->warmupDistance = $warmupTime * $result->athlete->warmupSpeed();
        $result->activeDistance = $oneIntervalDistance;
        $result->activePace = $activePace;
        $result->recoveryDistance = $oneRecoveryDistance;
        $result->repetitions = $reps;
        $result->cooldownDistance = $cooldownTime * $result->athlete->cooldownSpeed();
        $result->totalTime = $warmupTime + $periodicTrainingDuration + $cooldownTime;
        $result->periodicTrainingTime = $periodicTrainingDuration;

        return $result;
    }

    static function makeTempoRun(float $totalDistance, float $last10kTime, int $reps, float $allure): Workout {
        $result = new Workout($last10kTime);
        $warmupTime = 15;
        $cooldownTime = 10;
        $result->repetitions = $reps;
        $result->warmupDistance = $result->athlete->warmupSpeed() * $warmupTime;
        $result->cooldownDistance = $result->athlete->cooldownSpeed() * $cooldownTime;
        $result->repetitionDistance = $totalDistance - $result->warmupDistance - $result->cooldownDistance;

        $activeSpeed = $allure ? $result->athlete->predictedRaceSpeedFromDistance($allure) : $result->athlete->predictedRaceSpeedFromDistance($result->repetitionDistance);
        $recoverySpeed = $result->athlete->recoverySpeed();

        $result->recoveryDistance = $result->repetitionDistance * $recoverySpeed / (2 * $activeSpeed + $recoverySpeed);
        $result->activeDistance = $result->repetitionDistance - $result->recoveryDistance;
        $result->activePace = 1 / $activeSpeed;
        //$result->recoveryPace = 1 / $recoverySpeed;

        $result->activeDistance = $result->activeDistance / $reps;
        $result->recoveryDistance = $result->recoveryDistance / $reps;
        $result->periodicTrainingTime = $result->activeDistance / $activeSpeed + $result->recoveryDistance / $recoverySpeed;
        $result->totalTime = $warmupTime + $result->periodicTrainingTime + $cooldownTime;
        $result->type = self::tempo;
        return $result;
    }

    static function makeLongRun(float $distance, float $last10kTime, float $allure): Workout {
        $result = self::makeSimpleRun($distance, $last10kTime, $allure);
        $result->type = self::long;
        return $result;
    }

    static function makeSimpleRun(float $distance, float $last10kTime, float $allure): Workout {
        $result = new Workout($last10kTime);
        $warmupTime = 10;
        $cooldownTime = 10;
        $result->repetitions = 1;
        $result->warmupDistance = $result->athlete->warmupSpeed() * 10;
        $result->cooldownDistance = $result->athlete->cooldownSpeed() * 10;
        $result->repetitionDistance = $distance - $result->warmupDistance - $result->cooldownDistance;

        if ($result->repetitionDistance < 1) {
            $result->repetitionDistance = $distance;
            $result->warmupDistance = 0;
            $result->cooldownDistance = 0;
        }
        $result->activeDistance = $result->repetitionDistance;
        $activeSpeed = $result->athlete->predictedRaceSpeedFromDistance($allure);
        $result->activePace = $result->athlete->predictedRacePaceFromDistance($allure);
        $result->periodicTrainingTime = $result->activeDistance / $activeSpeed;
        $result->totalTime = $warmupTime + $result->periodicTrainingTime + $cooldownTime;
        $result->type = self::base;

        return $result;
    }

    static function makeRest() {
        $result = new Workout(1000);
        $result->repetitionDistance = 0;
        $result->warmupDistance = 0;
        $result->cooldownDistance = 0;
        $result->totalTime = 0;
        $result->type = self::rest;

        return $result;
    }

    function duration(): float {
        return $this->totalTime;
    }

    function distance(): float {
        return $this->warmupDistance + $this->repetitions * ($this->activeDistance + $this->recoveryDistance) + $this->cooldownDistance;
    }

    function justRepsToString(): string {
        $result = $this->repetitions . " x ( ";
        $result .= $this->nearest100m($this->activeDistance);
        $result .= '@' . Tools::decimalMinutesToHMSstring($this->activePace);
        $result .= $this->recoveryDistance >= 0.1 ? ' + ' . $this->nearest100m($this->recoveryDistance) . '@' . Tools::decimalMinutesToHMSstring($this->athlete->recoveryPace()) : '';
        $result .= ' )';
        return $result;
    }

    function setDoDate(DateTimeImmutable $date) {
        $this->doDate = $date;
    }

    function doDate(): ?DateTimeImmutable {
        return $this->doDate;
    }

    function type(): int {
        return $this->type;
    }

    function typeString(): string {
        return self::typeAttributes[$this->type()]['caption'];
    }

    function typeColor(): string {
        return self::typeAttributes[$this->type()]['color'];
    }

    function toString($sep = ''): string {
        if ($this->distance() > 1) {
            $result = $this->warmupDistance > 0.0 ? 'warm ' . $this->nearest100m($this->warmupDistance) . '@' . Tools::decimalMinutesToHMSstring($this->athlete->warmupPace()) . ' + ' . $sep: '';
            $result .= $this->repetitions > 1 ? $this->repetitions . " x ( " : '';

            $result .= $this->nearest100m($this->activeDistance);
            $result .= '@' . Tools::decimalMinutesToHMSstring($this->activePace);

            $result .= $this->recoveryDistance >= 0.1 ? ' + ' . $this->nearest100m($this->recoveryDistance) . '@' . Tools::decimalMinutesToHMSstring($this->athlete->recoveryPace()) : '';

            $result .= $this->repetitions > 1 ? ' )' . $sep : '';
            $result .= $this->cooldownDistance > 0.0 ? ' + ' . $this->nearest100m($this->cooldownDistance) . '@' . Tools::decimalMinutesToHMSstring($this->athlete->cooldownPace()) . ' cool' : '';
        } else {
            $result = 'rest';
        }

        return $result;
    }

    function makeGraphBlock(float $pace, float $duration): Tag {
        $speed = $pace ? 1 / $pace : 1 / (1e-6 + $pace);
        $baselineSpeed = 0.6 * $this->athlete->recoverySpeed();
        $ratio = ($speed - $baselineSpeed) / ($this->athlete->mileSpeed() - $baselineSpeed);

        $h = $ratio > 1 ? 100 : 100 * $ratio;
        $w = 100 * $duration;
        list($zoneLabel, $zoneColor) = $this->athlete->zoneFromPace($pace);
        $div = Tag::make('div', '', ['class' => 'clubTrackSessionPhase workoutPhaseToolTip', 'style' => "height: $h%; width: $w%; background-color: $zoneColor;"]);
        $div->makeChild('div', Tools::decimalMinutesToHMSstring($pace), ['class' => 'workoutPhaseToolTipText']);
        $div->makeChild('div', Tools::decimalKtoK100Mstring($duration), ['class' => 'workoutPhaseToolTipText']);

        return $div;
    }

    function makeGraph(): Tag {
        $div = Tag::make('div', '', ['class' => 'clubTrackSession workoutToolTip']);

        $div->makeChild('div', 'distance ' . round($this->distance(), 1) . ' km', ['class' => 'workoutToolTipText']);
        $div->makeChild('div', 'duration ' . Tools::decimalMinutesToHMSstring($this->duration()), ['class' => 'workoutToolTipText']);

        $div->addChild($this->makeGraphBlock($this->athlete->warmupPace(), $this->warmupDistance));
        for ($i = 0; $i < $this->repetitions; $i++) {
            $div->addChild($this->makeGraphBlock($this->activePace, $this->activeDistance));
            $div->addChild($this->makeGraphBlock($this->athlete->recoveryPace(), $this->recoveryDistance));
        }
        $div->addChild($this->makeGraphBlock($this->athlete->cooldownPace(), $this->cooldownDistance));
        return $div;
    }

    function toPreJSON(): array {

        $dps = 1;
        $dps2 = 3;
        $warmupDistance = round($this->warmupDistance, $dps);
        $activeDistance = round($this->activeDistance, $dps);
        $recoveryDistance = round($this->recoveryDistance, $dps);
        $cooldownDistance = round($this->cooldownDistance, $dps);
        $warmupSpeed = round($this->athlete->warmupSpeed(), $dps2);
        $activeSpeed = round($this->activePace ? 1 / $this->activePace : 0, $dps2);
        $recoverySpeed = round($this->athlete->recoverySpeed(), $dps2);
        $cooldownSpeed = round($this->athlete->cooldownSpeed(), $dps2);

        $warmupPhase = ['type' => 'warmup', 'distance' => $warmupDistance, 'speed' => $warmupSpeed];
        $activePhase = ['type' => 'active', 'distance' => $activeDistance, 'speed' => $activeSpeed];
        $recoveryPhase = ['type' => 'recovery', 'distance' => $recoveryDistance, 'speed' => $recoverySpeed];
        $cooldownPhase = ['type' => 'cooldown', 'distance' => $cooldownDistance, 'speed' => $cooldownSpeed];
        if ($this->repetitions > 1) {
            $reps = ['type' => 'repetition', 'reps' => $this->repetitions, 'workout' => [$activePhase, $recoveryPhase]];
            $workout = [$warmupPhase, $reps, $cooldownPhase];
        } else {
            $workout = [$warmupPhase, $activePhase, $recoveryPhase, $cooldownPhase];
        };

        $workoutWithContext = [
            'date' => $this->doDate() ? $this->doDate()->format('D d-m-y') : '',
            'type' => $this->typeString(),
            'string' => $this->toString("\n"),
            'workout' => $workout,
        ];

        return $workoutWithContext;
    }

    // these are time based, no choice (ignore metric tags)
    function toZwiftCyclingWorkout(string $title = 'My Custom Workout'): Tag {
        $zw = new ZwiftWorkout($this->athlete->last10kTime(), $title, 'Bike');
        $zw->addWarmup($this->warmupDistance * $this->athlete->warmupPace());
        if ($this->repetitions > 1) {
            $zw->addRep($this->activeDistance * $this->activePace, $this->activePace, $this->repetitions, $this->recoveryDistance * $this->athlete->recoveryPace());
        } else {
            $zw->addSteady($this->activeDistance * $this->activePace, $this->activePace);
            if ($this->recoveryDistance) {
                $zw->addSteady($this->recoveryDistance * $this->athlete->recoveryPace(), $this->athlete->recoveryPace());
            }
        }
        $zw->addCooldown($this->cooldownDistance * $this->athlete->recoveryPace());
        return $zw->toTag();
    }

    function toZwiftRunningWorkout(string $title = 'My Custom Workout'): Tag {
        $stretch = 1000;
        $zw = new ZwiftWorkout($this->athlete->last10kTime(), $title, 'Run');
        $zw->addWarmup($stretch * $this->warmupDistance);
        if ($this->repetitions > 1) {
            $zw->addRep($stretch * $this->activeDistance, $this->activePace, $this->repetitions, $stretch * $this->recoveryDistance);
        } else {
            $zw->addSteady($stretch * $this->activeDistance, $this->activePace);
            if ($this->recoveryDistance) {
                $zw->addSteady($stretch * $this->recoveryDistance, $this->athlete->recoveryPace());
            }
        }
        $zw->addCooldown($stretch * $this->cooldownDistance);
        return $zw->toTag();
    }

    function periodicTrainingDistance(): float {
        return $this->repetitionDistance;
    }

    function periodicTrainingDuration(): float {
        return $this->periodicTrainingTime;
    }

    function activePace(): float {
        return $this->activePace;
    }

    private function nearest100m_(float $kilometers): string {
        $result = round($kilometers, 1); // nearest 100 m or a tenth of a kilometer
        $postPend = '';

        $result = $result < 1 ? 1000 * $result . 'm' : $result . 'km';
        return $result;
    }

    private function nearest100m(float $kilometers): string {
        $meters100 = $kilometers * 10;
        $roundMeters100 = round($meters100); // nearest 100 m or a tenth of a kilometer
        $result = $roundMeters100 / 10;
        $postPend = '';

        $result = $result < 1 ? 1000 * $result . 'm' : $result . 'km';
        return $result;
    }
}
