<?php

class ClubTrackSessionsBucket {

    protected array $sessions;

    function add(ClubTrackSession $session) {
        $this->sessions[] = $session;
    }

    function n(): int {
        return sizeof($this->sessions);
    }

    function distance(): float {
        $sum = 0;
        $n = 0;
        foreach ($this->sessions as $session) {
            $sum += $session->distance();
            $n++;
        }
        return $n ? $sum / $n : 0;
    }

    function lowToHighDuration(): array {
        $lowDuration = 1e10;
        $highStress = 0;
        foreach ($this->sessions as $session) {
            $lowDuration = $lowDuration > $session->duration() ? $session->duration() : $lowDuration;
            $highStress = $highStress < $session->duration() ? $session->duration() : $highStress;
        }
        return [$lowDuration, $highStress];
    }

    function lowToHighStress(): array {
        $lowStress = 1e10;
        $highStress = 0;
        foreach ($this->sessions as $session) {
            $lowStress = $lowStress > $session->stress() ? $session->stress() : $lowStress;
            $highStress = $highStress < $session->stress() ? $session->stress() : $highStress;
        }
        return [$lowStress, $highStress];
    }

    function lowToHighDurationString(): string {
        list($lowDuration, $highDuration) = $this->lowToHighDuration();
        return $lowDuration == $highDuration ? Tools::decimalMinutesToHMSstring($lowDuration) : Tools::decimalMinutesToHMSstring($lowDuration) . ' to ' . Tools::decimalMinutesToHMSstring($highDuration);
    }

    function lowToHighStressString(): string {
        list($lowStress, $highStress) = $this->lowToHighStress();
        return (int)round($lowStress, 1) == (int)round($highStress, 1) ? (int)round($lowStress) : (int)round($lowStress) . ' to ' . (int)round($highStress);
    }

    function averageDuration(): float {
        $sum = 0;
        $n = 0;
        foreach ($this->sessions as $session) {
            $sum += $session->duration();
            $n++;
        }
        return $n ? $sum / $n : 0;
    }

    function averageStress(): float {
        $sum = 0;
        $n = 0;
        foreach ($this->sessions as $session) {
            $sum += $session->stress();
            $n++;
        }
        return $n ? $sum / $n : 0;
    }

    function low10kTime(): int {
        return $this->sessions[0]->athlete()->last10kTime();
    }

    function high10kTime(): int {
        return end($this->sessions)->athlete()->last10kTime();
    }

    function lowToHigh10kTimeString() {
        return $this->low10kTime() === $this->high10kTime() ? $this->low10kTime() : $this->low10kTime() . ' to ' . $this->high10kTime();
    }

    function allures(): array {
        return $this->sessions[0]->allures();
    }

    function paces(float $overDistance = 1.0): string {
        $result = '';
        $allures = $this->allures();
        foreach ($allures as $allure) {
            $lowPace = $this->sessions[0]->athlete()->predictedRacePaceFromDistance($allure);
            $highPace = end($this->sessions)->athlete()->predictedRacePaceFromDistance($allure);

            if ($lowPace === $highPace) {
                $result .= Tools::decimalMinutesToHMSstring($overDistance * $lowPace);
            } else {
                $steps = $this->n() - 1;
                $stepsOfPace = $steps ? ($highPace - $lowPace) / $steps : 0;
                $result .= Tools::decimalMinutesToHMSstring($overDistance * $lowPace);
                $result .= $steps > 0 ? ' à ' . Tools::decimalMinutesToHMSstring($overDistance * $highPace) : '';
                $result .= $steps > 1 ? ' é ' . Tools::decimalMinutesToHMSstring($overDistance * $stepsOfPace) : '';
            }
            $result .= '<br>';
        }
        return $result;
    }
}
