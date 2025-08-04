<?php

//include_once '../../Common/PHP/all.php';

class ZwiftWorkout {

    public static int $warmup = 1;
    public static int $steady = 2;
    public static int $intervals = 3;
    public static int $cooldown = 4;
    protected static int $rampup = 0;
    protected static int $rampdown = 5;
    protected static int $rampingDistance = 800;
    protected ZwiftAthlete $athlete;
    protected Tag $result;
    protected Tag $workout;

    function __construct(float $last10kTime, string $name = 'My Custom Workout', string $sportType = 'Run') {
        $this->athlete = new ZwiftAthlete($last10kTime);
        $this->result = Tag::make('workout_file');
        $this->result->makeChild('author', 'run.drewshardlow.com');
        $this->result->makeChild('name', $name);
        $this->result->makeChild('description', 'Structured Workout');
        $this->result->makeChild('sportType', $sportType);
        $this->result->makeChild('durationType', $sportType == 'Run' ? 'distance' : 'time');
        $this->result->makeChild('tags')->makeChild('tag', '', ['name' => 'INTERVALS']);
        $this->result->makeChild('durationType', 'distance');
        $this->workout = $this->result->makeChild('workout');
    }

    private function _addPhase(int $type, float $activeDistance, float $pace = 0, int $reps = 1, float $recoveryDistance = 0) {
        $attributes = ['pace' => '0'];
        $paceString = Tools::decimalMinutesToHMSstring($pace,[' ',':','']);
        $roundedActiveDistance = round($activeDistance, -2);
        $roundedRecoveryDistance = round($recoveryDistance, -2);
        $description = '';
        switch ($type) {
            case self::$rampup:
                $description = 'Ramping up slowly over ' . $roundedActiveDistance . 'm';
                $phaseType = 'Warmup';
                $attributes['Duration'] = $activeDistance;
                $attributes['PowerLow'] = 0.5;
                $attributes['PowerHigh'] = $this->athlete->warmupPower();
                break;
            case self::$warmup:
                $description = 'Warming up steadily over ' . $roundedActiveDistance . 'm';
                $phaseType = 'SteadyState';
                $attributes['Duration'] = $activeDistance;
                $attributes['Power'] = $this->athlete->warmupPower();
                break;
            case self::$steady:
                $description = 'Running at ' . $paceString . ' over ' . $roundedActiveDistance . 'm';
                $phaseType = 'SteadyState';
                $attributes['Duration'] = $activeDistance;
                $attributes['Power'] = $this->athlete->paceToPower($pace);
                break;
            case self::$intervals:
                $description = 'Run interval at ' . $paceString . ' over ' . $roundedActiveDistance . 'm';
                $phaseType = 'IntervalsT';
                $attributes['Repeat'] = $reps;
                $attributes['OnDuration'] = $activeDistance;
                $attributes['OffDuration'] = $recoveryDistance;
                $attributes['OnPower'] = $this->athlete->paceToPower($pace);
                $attributes['OffPower'] = $this->athlete->recoveryPower();
                break;
            case self::$cooldown:
                $description =  'Cooling down steadily over ' . $roundedActiveDistance . 'm';
                $phaseType = 'SteadyState';
                $attributes['Duration'] = $activeDistance;
                $attributes['Power'] = $this->athlete->coodownPower();
                break;
            case self::$rampdown:
                $description = 'Ramping down slowly over ' . $roundedActiveDistance . 'm';
                $phaseType = 'Cooldown';
                $attributes['Duration'] = $activeDistance;
                $attributes['PowerLow'] = 0.5;
                $attributes['PowerHigh'] = $this->athlete->coodownPower();
                break;

            default:
                $phaseType = 'Unknown';
                break;
        }

        $phase = $this->workout->makeChild($phaseType, '', $attributes);
        
        // add a comment to the Zwift workout
        $phase->makeChild('textevent','',['timeoffset'=>'1', 'message'=>$description]);
    }

    public function toTag(): Tag {
        return $this->result;
    }

    public function addPhase(int $phaseType, float $activeDistance, float $pace = 0, int $reps = 1, $recoveryDistance = 0) {
        switch ($phaseType) {

            case ZwiftWorkout::$warmup:
                if (self::$rampingDistance > $activeDistance) {
                    $this->_addPhase(self::$rampup, $activeDistance);
                } else {
                    $this->_addPhase(self::$rampup, self::$rampingDistance);
                    $this->_addPhase(self::$warmup, $activeDistance - self::$rampingDistance);
                }
                break;

            case ZwiftWorkout::$steady:
                $this->_addPhase(self::$steady, $activeDistance, $pace);
                break;

            case ZwiftWorkout::$intervals:
                $this->_addPhase(self::$intervals, $activeDistance, $pace, $reps, $recoveryDistance);
                break;

            case ZwiftWorkout::$cooldown:
                if (self::$rampingDistance > $activeDistance) {
                    $this->_addPhase(self::$rampdown, $activeDistance);
                } else {
                    $this->_addPhase(self::$cooldown, $activeDistance - self::$rampingDistance);
                    $this->_addPhase(self::$rampdown, self::$rampingDistance);
                }
                break;

            default:
                break;
        }
    }

    public function addWarmup(float $distance) {
        $this->addPhase(self::$warmup, $distance);
    }

    public function addSteady(float $distance, float $pace) {
        $this->addPhase(self::$steady, $distance, $pace);
    }

    public function addRep(float $activeDistance, float $pace, int $reps, float $recoveryDistance) {
        $this->addPhase(self::$intervals, $activeDistance, $pace, $reps, $recoveryDistance);
    }

    public function addCooldown(float $distance) {
        $this->addPhase(ZwiftWorkout::$cooldown, $distance);
    }
    
    public function addStandardPhase(){
        $this->addPhase(ZwiftWorkout::$steady, Constants::MILE_LENGTH_IN_KILOMETERS, $this->athlete->predictedRacePaceFromDistance(Constants::MILE_LENGTH_IN_KILOMETERS));
    }
    
    
    public function graphTag():Tag{
        
      
    }
}




/*


$Z = new ZwiftWorkout(53);

$Z->addWarmup(1000);
$Z->addSteady(2000, 6.5);
$Z->addRep(400, 5, 8, 200);
$Z->addCooldown(1000);


$pre = Tag::make('pre');
$zwiftTag = $Z->getTag();
$zwiftTag->setLiterality(true);
$pre->addChild($zwiftTag);
$pre->echo();
 * 
 * 
 */



