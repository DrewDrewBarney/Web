<?php

class FITdebug{
    
    public static bool $debug = false;
    
    
    public static function echo($val):void{
        if (self::$debug){
            echo $val;
        }
    }
    
}

