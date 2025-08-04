<?php

class Tools {
    
     
    /*
     * JAVASCRIPT STRINGS
     */
    
    public static string $restoreScrollPosition = "
        function scrollNice(){
            try{
                if (location.search){
                    let yString = window.localStorage.getItem('scrollY');
                    let y = parseInt(yString);
                    window.scrollTo(0, y);
                }
                //window.alert('ok');
            } catch(err){
                //window.alert('error');
                window.localStorage.setItem('scrollY', '0');
            }
        }
        scrollNice();
        ";
    
    public static string $saveScrollPosition = "                
        window.localStorage.setItem('scrollY', window.scrollY.toString());
        ";

    public static function delayedPopup(string $msg, float $delay): void{
        $script =
        "
            function delayedPopup(){
                window.alert('$msg');
            }
            setTimeout(delayedPopup,$delay);
        ";
        Tag::make('script', $script)->echo();
    }
    

    static public function decimalMinutesToHMSstring(float $decimalMinutes, array $separators = [' ', "'", "''"]):string {
        $wholeSecs = (int)round(60 * $decimalMinutes);
        $wholeMins = intdiv($wholeSecs, 60);
        $wholeHours = intdiv($wholeSecs, 3600);

        $hours = $wholeHours;
        $mins = $wholeMins % 60;
        $secs = $wholeSecs % 60;

        list($hmSep, $msSep, $s) = $separators;
        if ($hours) {
            return sprintf("%01d$hmSep%02d$msSep%02d$s", $hours, $mins, $secs);
        } else if ($mins) {
            return sprintf("%01d$msSep%02d$s", $mins, $secs);
        } else {
            return sprintf("%01d$s", $secs);
        }
    }

    /*
      function decimalMinutesToSecsString(float $decimalMinutes, array $separators = [' ', "'", "''"]) {
      $secs = round(60 * $decimalMinutes);
      list($hmSep, $msSep, $s) = $separators;
      return sprintf("%01d$s", $secs);
      }
     * *
     */

    static public function decimalMinutesFromString(string $minsSecs):float {
        $result = 0;
        preg_match_all('!\d+\.*\d*!', $minsSecs, $matches);
        //print_r($values);
        if (sizeof($matches) == 1) {
            $values = $matches[0];
            if (sizeof($values) == 1) {
                $result = intval($values[0]);
            } else if (sizeof($values) == 2) {
                $result = intval($values[0]) + intval($values[1]) / 60;
            }
        }
        return $result;
    }
    
    static public function decimalKtoK100Mstring(float $distance, int $quantize = 2):string{
        // quantization - the distance rounded to nearest 10^quantisation 
        return $distance < 1 ? round(1000 * $distance, $quantize) . 'm' : round($distance, 3 - $quantize ) . 'km';
    }
    
    static public function dayOfWeekStringFromDayOfWeekSerial(int $serial){
        return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'][$serial];
    }

    /*
      function showGetPost() {
      $result = '';
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $result = "POST\n";
      foreach($_POST as $key=>$value){
      $result .= $key . '=>' . $value . "\n";
      }
      } else {
      $result = "GET\n";
      foreach($_GET as $key=>$value){
      $result .= $key . '=>' . $value . "\n";
      }
      }
      echo '<pre>' . $result . '</pre>';
      }
     */

    static public function uniquePageID():int {
        static $id = 1000;
        return ($id++);
    }

    static public function interpolate(float $a, float $p, float $b): float {
        return $a + $p * ($b - $a);
    }

    static public function cookieValue($key) {
        return array_key_exists($key, $_COOKIE) ? htmlspecialchars($_COOKIE[$key]) : null;
    }

    static public function returnSelectedButtonValue(array $values): string {
        foreach ($values as $tag) {
            if ($tag->value()) {
                return $tag->value();
            }
        }
        return '';
    }
    
   /*
    * DATE METHODS
    */

    static public function now(): DateTimeImmutable {
        return new DateTimeImmutable("now");
    }
    
    static public function today():DateTimeImmutable{
        return new DateTimeImmutable("today");       
    }

    static public function dayOfWeekFromDate(DateTimeImmutable $date): int {
        $day = intval($date->format('N'));
        return (6 + $day) % 7;
    }
    
    static public function dayOfWeekStringFromDate(DateTimeImmutable $date): string{
        return $date->format('D');
    }
    

    static public function weekOfYearFromDate(DateTimeImmutable $date): int {
        return intval($date->format("W"));
    }
    
    
   static public function keyForValue(array $keyValuePairs, $valueAsKey){
       foreach ($keyValuePairs as $key => $value) {
           if ($valueAsKey == $value){
               return $key;
           }
       }
       return null;
   } 
   
  
    
    
}

/*
function pseudoRandomize(int $arraySize, int $range): array {
    $results = [];

    srand(333);
    $results[] = rand(0, $range - 1);

    do {
        $candidate = rand(0, $range - 1);
        if (end($results) != $candidate) {
            $results[] = $candidate;
        }
    } while (sizeof($results) < $arraySize);

    return $results;
}
*/

