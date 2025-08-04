<?php

class ClubTrackSession {

    public const VARY_NUMBERS = 0;
    public const VARY_BOTH = 1;
    public const VARY_DISTANCES = 2;
    public const CAPTION_VALUES = [
        'faire varier uniquement le nombre de répétitions' => self::VARY_NUMBERS,
        'varier le nombre et la distance de répétition' => self::VARY_BOTH,
        'faire varier uniquement la distance de répétition' => self::VARY_DISTANCES,
    ];

    //
    protected array $phases = [];
    protected ?Athlete $athlete;

    function __construct(?Athlete $athlete = null) {
        $this->athlete = $athlete;
    }

    function grow(int $by): void {
        $result = [];
        for ($i = 0; $i < $by; $i++) {
            $result = array_merge($result, $this->phases);
        }
        $this->phases = $result;
    }

    function setAthlete(Athlete $athlete) {
        $this->athlete = $athlete;
    }

    function athlete(): Athlete {
        return $this->athlete;
    }

    function addPhase(ClubTrackSessionPhase $phase) {
        //$phase->setAthlete($this->athlete); // a copy of the session athlete is added to each phase for its calcuations
        $this->phases[] = $phase;
    }
    
    function phases(): array{
        return $this->phases;
    }

    function duration(): float {
        $result = 0.0;
        foreach ($this->phases as $phase) {
            $result += $phase->duration($this->athlete);
        }
        return $result;
    }

    function distance(): float {
        $result = 0.0;
        foreach ($this->phases as $phase) {
            $result += $phase->distance();
        }
        return $result;
    }

    function makeScaled(float $desiredDuration, float $last10kTime, int $focus): ClubTrackSession {
        //$focus = self::CAPTION_VALUES[$focusString];
        $this->setAthlete(new Athlete($last10kTime));
        $scaleFactor = $this->duration() ? $desiredDuration / $this->duration() : 1;
        $scaledSession = new ClubTrackSession(new Athlete($last10kTime));
        foreach ($this->phases as $phase) {
            $scaledSession->addPhase($phase->makeScaled($scaleFactor, $focus));
        }
        return $scaledSession;
    }

    function stress(): float {
        $result = 0.0;
        foreach ($this->phases as $phase) {
            $result += $phase->runningStress($this->athlete);
        }
        return $result;
    }

    function toString($sep = ''): string {
        $result = '';
        foreach ($this->phases as $phase) {
            $result .= $phase->toString() . $sep;
        }
        return $result;
    }

    function toPreJSON(): array {
        $nakedWorkout = [];
        foreach ($this->phases as $phase) {
            $nakedWorkout[] = $phase->toPreJSON($this->athlete);
        }

        $workoutWithContext = [
            'date' => 'Mecredi',
            'type' => 'track',
            'string' => $this->toString("\n"),
            'workout' => $nakedWorkout,
        ];

        return $workoutWithContext;
    }

    function makeDisplay(): Tag {
        $result = Tag::make('div', '', ['class' => 'clubTrackSession']);
        foreach ($this->phases as $phase) {
            $blocks = $phase->makePhaseBlocks();
            foreach ($blocks as $block) {
                $result->addChild($block);
            }
        }
        return $result;
    }

    /*
      function toAlluresString(): string {
      $result = '';
      foreach ($this->phases as $phase) {
      $result .= $phase->toAllureString() . '<br>';
      }
      return $result;
      }
     * 
     */

    function allures(): array {
        $result = [];
        foreach ($this->phases as $phase) {
            $result[] = $phase->allure();
        }
        //asort($result);
        return $result;
    }
}
