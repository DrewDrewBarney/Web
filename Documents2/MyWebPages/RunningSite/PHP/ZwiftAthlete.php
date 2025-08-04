<?php

class ZwiftAthlete extends Athlete{
    
    
    function __construct(float $last10kTime) {
        parent::__construct($last10kTime);     
    }
    
    function warmupPower(): float{
        return $this->warmupSpeed() / $this->mileSpeed();
    }
    
    function coodownPower(): float{
        return $this->cooldownSpeed() / $this->mileSpeed();
    }
    
    function recoveryPower(): float{
        return $this->recoverySpeed() / $this->mileSpeed();
    }
    
    function paceToPower(float $pace): float{
        $speed = $pace ? 1 / $pace : 0;
        return $speed / $this->mileSpeed();
    }
    
    
}