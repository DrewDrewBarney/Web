<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Riegel {

    private float $exponent = 1.06;
    private float $lastKilometers;
    private float $lastMinutes;
    private float $relaxation = 7;

    function __construct($lastMinutes, $lastKilometers) {
        $this->lastKilometers = $lastKilometers;
        $this->lastMinutes = $lastMinutes;
    }

    function raceTimeForDistance(float $kilometers): float {
        if ($kilometers > 0) {
            return $this->lastMinutes * ($kilometers / $this->lastKilometers) ** $this->exponent;
        } else {
            return 0;
        }
    }

    function setRelaxation($relaxation): void {
        $this->relaxation = $relaxation;
    }

    function racePaceForDistance(float $kilometers): float {
        return $this->raceTimeForDistance($kilometers) / $kilometers;
    }

    function intervalPaceForDistance(float $kilometers): float {
        return $this->racePaceForDistance($this->relaxation * $kilometers);
    }
}

function getMarathonSpeedFromCriticalSpeed(float $Sc) {

    /*
     * 
     * Marathon speed vs critical speed
     * 
     * This is tricky as marathon speed as a proportion of critical speed is determined by the 
     * the duration of the run
     * 
     * https://www.outsideonline.com/health/training-performance/critical-speed-marathon-prediction-study/
     * 
     * https://pubmed.ncbi.nlm.nih.gov/32472926/
     * 
     * 0.930 for 150 min marathon
     * 0.789 for 360
     * 
     *   
     *
     */

// calculate gradient and intercept of the graph of the proportion of critical speed vs. marathon duration

    $Dm = 42.2; // marathon distance

    $b = (0.789 - 0.930) / (360 - 150); // work out the gradient
    $a = 0.789 - $b * 360; // work out the intercept
    // 
    // solve the quadratic

    $Tm = (-$a + sqrt(pow($a, 2) + 4 * $b * $Dm/$Sc)) / (2 * $b);
    
    $fitness = 1.0;
    
    $Sm = (0.94 * $Sc) * $fitness + (1.0 - $fitness) * $Dm / $Tm;
    
    return $Sm;
}
