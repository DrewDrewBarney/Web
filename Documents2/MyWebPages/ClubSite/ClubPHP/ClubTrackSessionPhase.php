<?php


class ClubTrackSessionPhase {

    protected float $reps;
    protected float $activeDistance;
    protected int $allure;
    protected float $recoveryDistance;

    function __construct(float $reps, float $activeDistance, int $allure, float $recoveryDistance) {
        $this->reps = $reps;
        $this->activeDistance = $activeDistance; // get km from meters passed
        $this->allure = $allure;
        $this->recoveryDistance = $recoveryDistance;
    }

    function duration(Athlete $athlete): float {
        $activeDuration = $this->activeDistance * $athlete->predictedRacePaceFromDistance($this->allure);
        $recoveryDuration = $this->recoveryDistance * $athlete->recoveryPace();
        return $this->reps * ($activeDuration + $recoveryDuration);
    }

    function distance(): float {
        return $this->reps * ($this->activeDistance + $this->recoveryDistance);
    }

    function makeScaled(float $scaleFactor, int $focus): ClubTrackSessionPhase {

        switch ($focus) {
            case ClubTrackSession::VARY_NUMBERS:
                $repsScaleFactor = $scaleFactor;
                $distancesScaleFactor = 1;
                $scaledReps = (int)round($this->reps * $repsScaleFactor);
                $scaledReps = $scaledReps ? $scaledReps : 1;

                //$repsScaleFactor = $scaledReps / $this->reps;               
                //$distancesScaleFactor = $scaleFactor / $repsScaleFactor;

                $scaledActiveDistance = round($this->activeDistance * $distancesScaleFactor, 1);
                $scaledRecoveryDistance = round($this->recoveryDistance * $distancesScaleFactor, 1);
                break;

            case ClubTrackSession::VARY_DISTANCES;
                $repsScaleFactor = 1;
                $distancesScaleFactor = $scaleFactor;
                $scaledActiveDistance = round($this->activeDistance * $distancesScaleFactor, 1);
                $scaledRecoveryDistance = round($this->recoveryDistance * $distancesScaleFactor, 1);
                /*
                  $scaledTotalDistance = $scaledActiveDistance + $scaledRecoveryDistance;
                  $distancesScaleFactor = $scaledTotalDistance / ($this->activeDistance + $this->recoveryDistance);
                  $repsScaleFactor = $scaleFactor / $distancesScaleFactor;
                  $scaledReps = round($repsScaleFactor * $this->reps);
                  $scaledReps = $scaledReps ? $scaledReps : 1;
                 */
                $scaledReps = $this->reps;
                break;

            case ClubTrackSession::VARY_BOTH:
            default:
                $repsScaleFactor = $scaleFactor ** 0.5;
                $distancesScaleFactor = $repsScaleFactor;

                $scaledReps = (int)round($repsScaleFactor * $this->reps);
                $scaledReps = $scaledReps ? $scaledReps : 1;
                $repsScaleFactor = $scaledReps / $this->reps;
                $distancesScaleFactor = $scaleFactor / $repsScaleFactor;

                $scaledActiveDistance = round($this->activeDistance * $distancesScaleFactor, 1);
                $scaledRecoveryDistance = round($this->recoveryDistance * $distancesScaleFactor, 1);

                break;
        }


        return new ClubTrackSessionPhase($scaledReps, $scaledActiveDistance, $this->allure, $scaledRecoveryDistance);
    }

    function runningStress(Athlete $athlete): float {
        $thresholdSpeed = $athlete->predictedRaceSpeedFromTime(60);

        $activeIntensity = ($athlete->predictedRaceSpeedFromDistance($this->allure) / $thresholdSpeed) ** 2;
        $activeDuration = $this->activeDistance * $athlete->predictedRacePaceFromDistance($this->allure);
        $activeRunningStress = $activeIntensity * $activeDuration;

        $recoveryIntensity = ($athlete->recoverySpeed() / $thresholdSpeed) ** 2;
        $recoveryDuration = $this->recoveryDistance * $athlete->recoveryPace();
        $recoveryRunningStress = $recoveryIntensity * $recoveryDuration;

        $totalStress = 100 / 60 * $this->reps * ($activeRunningStress + $recoveryRunningStress);
        return $totalStress;
    }

    function toString(): string {
        $result = '';
        $result .= $this->reps . ' x ( ';
        $result .= intval($this->activeDistance * 1000);
        $result .= '@' . sprintf('%02d', $this->allure);
        $result .= ' + ' . intval($this->recoveryDistance * 1000) . 'r';
        $result .= ' )';
        //$result .= '  as'.$this->allure;
        return $result;
    }
    
    function toPreJSON(Athlete $athlete): array{
        $dps = 1;
        $dps2 = 3;
        $activeDistance = round($this->activeDistance, $dps);
        $recoveryDistance = round($this->recoveryDistance, $dps);
        $activeSpeed = $athlete->predictedRaceSpeedFromDistance($this->allure);       
        $recoverySpeed = round($athlete->recoverySpeed(), $dps2);
        $activePhase = ['type' => 'active', 'distance' => $activeDistance, 'speed' => $activeSpeed];
        $recoveryPhase = ['type' => 'recovery', 'distance' => $recoveryDistance, 'speed' => $recoverySpeed];      
        $workoutPhase = ['type'=>'repetition', 'reps'=> $this->reps, 'workout'=>  [$activePhase, $recoveryPhase]];
        return $workoutPhase;
    }
        

    function allure(): int {
        return $this->allure;
    }

    function toAllureString(): string {
        return $this->allure;
    }
    
    function pace(Athlete $athlete):float{
        return $athlete->predictedRacePaceFromDistance($this->allure);
    }

    function blockColor(int $allure): string {
        $colors = [ 3 => 'red', 5 => 'violet', 7 => 'orange', 10 => 'yellow', 22 => 'lightgreen', 44 => 'cyan', 1000 => 'gray'];
        foreach ($colors as $key => $value) {
            if ($allure <= $key) {
                return $value;
            }
        }
        return end($colors);
    }

    function normalSpeeds(): array {
        $active = (1.0 / $this->allure) ** 0.06;
        $recovery = (1.0 / Athlete::RECOVERY_ALLURE) ** 0.06;
        // increase dynamic range so visually better
        $boost = 0.6;
        $active = ($active - $boost) / (1 - $boost);
        $recovery = ($recovery - $boost) / (1 - $boost);
        return [$active, $recovery];
    }

    function makePhaseBlocks(): array {
        list($active, $recovery) = $this->normalSpeeds();
        $h1 = (int)round(100 * $active);
        $h2 = (int)round(100 * $recovery);
        $w1 = 100 * $this->activeDistance;
        $w2 = 100 * $this->recoveryDistance;
        $c1 = $this->blockColor($this->allure);
        $c2 = $this->blockColor(100);

        $blocks = [];
        for ($i = 0; $i < $this->reps; $i++) {
            $blocks[] = Tag::make('div', $this->allure, ['class' => 'clubTrackSessionPhase', 'style' => "height: $h1%; width: $w1%; background-color: $c1;"]);
            $blocks[] = Tag::make('div', '', ['class' => 'clubTrackSessionPhase', 'style' => "height: $h2%; width: $w2%; background-color: $c2;"]);
        }
        return $blocks;
    }
}
