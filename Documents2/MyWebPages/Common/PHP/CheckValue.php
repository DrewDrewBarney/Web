<?php

//include_once '../../Common/PHP/all.php';

abstract class CheckValue{
    
    public bool $inError;
    public string $errorMessage;
    
    public function __construct() {
        $this->inError = false;
        $this->errorMessage = '';
    }
    
    public function inError():bool{
        return $this->inError;
    }
    
    public function errorMessage(): string{
        return $this->errorMessage;
    }
    
    abstract function check($value): bool;
}


class CheckInteger extends CheckValue{
    public int $low;
    public int $high;
    
    
    public function __construct(int $low, int $high) {
        parent::__construct();
        $this->low = $low;
        $this->high = $high;
    }
    
    
    public function check($value): bool {
        $val = intval($value);
        $this->inError = $val < $this->low || $val > $this->high;
        if ($this->inError){
            $this->errorMessage = 'integer value not in range';
        }
        return !$this->inError;
    }
}