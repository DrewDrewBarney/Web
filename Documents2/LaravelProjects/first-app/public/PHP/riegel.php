<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class Riegel{
    
    private float $exponent = 1.06;
    private float $lastKilometers;
    private float $lastMinutes;
    private float $relaxation = 7;
    
    
    function __construct($lastMinutes, $lastKilometers){
        $this->lastKilometers = $lastKilometers;
        $this->lastMinutes = $lastMinutes;
    }
    
    function raceTimeForDistance(float $kilometers): float{
        if ($kilometers > 0){
            return $this->lastMinutes * ($kilometers/$this->lastKilometers) ** $this->exponent;
        } else {
            return 0;
        }
    }
    
    function setRelaxation($relaxation): void{
        $this->relaxation = $relaxation;
    }
    
    
    function racePaceForDistance(float $kilometers): float{
        return $this->raceTimeForDistance($kilometers) / $kilometers;
    }
    
    function intervalPaceForDistance(float $kilometers): float{
        return $this->racePaceForDistance($this->relaxation * $kilometers);
    }
    
   
    
}

