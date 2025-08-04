<?php

include_once 'PHP/all.php';

final class DurationType {
    const time = 'time';
    const distance = 'distance';
    const open = 'open';
}

const DURATION_TYPES = array(DurationType::time, DurationType::distance, DurationType::open);


final class IntensityType {

    const hr = 0;
    const speed = 1;
    const power = 2;
    const free = 3;
}

final class ButtonOperationTypes {

    const prependStep = 'prepend_step';
    const appendStep = 'append_step';
    const appendWorkout = 'append_workout';
    const deleteStep = 'delete_step';
    const moveUp = 'move_up';
    const moveDown = 'move_down';
    const edit = 'edit';
    const ok = 'ok';
    const updateStepCaption = 'update_step_caption';
}

abstract class WorkoutBase {

    public string $domID;
    protected array $children = [];
    public bool $beingEdited;

    abstract protected function makeTags(): Tag;

    public function __construct() {
        $this->domID = uniqid();
        $this->children = [];
        $this->beingEdited = false;
    }

    public function findByID($id): ?WorkoutBase {
        if ($this->domID == $id) {
            return $this;
        } else {
            foreach ($this->children as $child) {
                $found = $child->findById($id);
                if ($found) {
                    return $found;
                }
            }
        }
        return null;
    }

    public function findParentOf($lostChild): ?WorkoutBase {
        foreach ($this->children as $child) {
            $parent = $child->findParentOf($lostChild);
            if ($parent) {
                return $parent;
            } else if ($child === $lostChild) {
                return $this;
            }
        }
        return null;
    }

    public function clearBeingEdited() {
        foreach ($this->children as $child) {
            $child->clearBeingEdited();
            $child->beingEdited = false;
        }
    }

    protected function registerForThePost(Tag $tag, string $humanName, string $action): Tag {
        $fullName = $humanName . '@' . $this->domID . '@' . $action;
        $tag->setAttributes(['name' => $fullName]);
        return $tag;
    }
}

class WorkoutStep extends WorkoutBase {

    public string $caption;
    public SelectField $durationType;
    public int $duration; // in seconds
    public $intensityType;
    public float $intensityTarget;

    public function __construct(string $name = 'any', string $durationType = DurationType::open, int $duration = 0, int $intensityType = IntensityType::free, float $intensity = 0.0) {
        parent::__construct();
        $this->caption = $name;
        $this->durationType = new SelectField(DURATION_TYPES, $durationType);
        $this->duration = $duration;
        $this->intensityType = $intensityType;
        $this->intensityTarget = $intensity;
        $this->beingEdited = false;
    }

    public function makeTags(): Tag {
        if ($this->beingEdited) {
            $stepClass = 'workoutStepEditing';
            $div = Tag::make('div', '', ['class' => $stepClass]);
            //$div->makeChild('div', $this->domID);
            $stepCaption = $div->makeChild('input', '', ['type'=>'text', 'value'=>$this->caption]);
            $this->registerForThePost($stepCaption, 'stepCaption', ButtonOperationTypes::updateStepCaption);
            $div->addChild($this->durationType->makeTags());
            //$durationTypeDiv->addChild(makeSelect(['one', 'two', 'three']));
            $div->makeChild('div', 'duration ' . $this->duration);
            $div->makeChild('div', 'intensity type ' . $this->intensityType);
            $div->makeChild('div', 'intensity target ' . $this->intensityTarget);

            $saveBtn = $div->makeChild('button', 'save', ['type' => 'submit']);
            $this->registerForThePost($saveBtn, 'saveBtn', ButtonOperationTypes::ok);
        } else {
            $stepClass = 'workoutStep';
            $div = Tag::make('div', '', ['class' => $stepClass]);
            //$div->makeChild('div', $this->domID);
            $div->makeChild('div', $this->caption);
            $div->makeChild('div', $this->durationType->toString());
            //$durationTypeDiv = $div->makeChild('div', 'duration type ' . $this->durationType);
            //$durationTypeDiv->addChild(makeSelect(['one', 'two', 'three']));
            $div->makeChild('div', 'duration ' . $this->duration);
            $div->makeChild('div', 'intensity type ' . $this->intensityType);
            $div->makeChild('div', 'intensity target ' . $this->intensityTarget);

            $delBtn = $div->makeChild('button', 'delete step', ['type' => 'submit']);
            $this->registerForThePost($delBtn, 'deleteStepBtn', ButtonOperationTypes::deleteStep);
            $upBtn = $div->makeChild('button', 'move up', ['type' => 'submit']);
            $this->registerForThePost($upBtn, 'moveUpBtn', ButtonOperationTypes::moveUp);
            $downBtn = $div->makeChild('button', 'move down', ['type' => 'submit']);
            $this->registerForThePost($downBtn, 'moveDownBtn', ButtonOperationTypes::moveDown);
            $editBtn = $div->makeChild('button', 'edit', ['type' => 'submit']);
            $this->registerForThePost($editBtn, 'editBtn', ButtonOperationTypes::edit);
        }

        return $div;
    }
}

class Workout extends WorkoutBase {

    public int $repeats = 1;

    public function __construct($repeats = 1) {
        parent::__construct();
        $this->repeats = $repeats;
    }

    public function prependStep(WorkoutStep $step): void {
        array_unshift($this->children, $step);
    }

    public function appendStep(WorkoutStep $step): void {
        $this->children[] = $step;
    }

    public function deleteStep(WorkoutStep $step): void {
        foreach ($this->children as $key => $child) {
            if ($child === $step) {
                unset($this->children[$key]);
                return;
            }
        }
    }

    public function prependWorkout(Workout $workout): void {
        array_unshift($this->children, $workout);
    }

    public function appendWorkout(Workout $workout): void {
        $this->children[] = $workout;
    }

    public function moveUp(WorkoutBase $target): void {
        for ($i = 0; $i < sizeof($this->children); $i++) {
            if ($target === $this->children[$i] && $i > 0) {
                $temp = $this->children[$i - 1];
                $this->children[$i - 1] = $target;
                $this->children[$i] = $temp;
                return;
            }
        }
    }

    public function moveDown(WorkoutBase $target): void {
        for ($i = 0; $i < sizeof($this->children); $i++) {
            if ($target === $this->children[$i] && $i < (sizeof($this->children) - 1)) {
                $temp = $this->children[$i + 1];
                $this->children[$i + 1] = $target;
                $this->children[$i] = $temp;
                return;
            }
        }
    }

    // creating tags stuff


    public function makeTags(): Tag {
        $div = Tag::make('div', '', ['class' => 'workout']);

        //$div->makeChild('div', $this->domID);
        if ($this->repeats > 1) {
            $div->makeChild('div', 'reps ' . $this->repeats);
        }


        foreach ($this->children as $child) {
            $div->addChild($child->makeTags());
        }



        $appendStepButton = $div->makeChild('button', 'add step', ['type' => 'submit']);
        $this->registerForThePost($appendStepButton, 'appendStepBtn', ButtonOperationTypes::appendStep);

        $appendWorkoutButton = $div->makeChild('button', 'add workout', ['type' => 'submit']);
        $this->registerForThePost($appendWorkoutButton, 'appendWorkoutBtn', ButtonOperationTypes::appendWorkout);

        return $div;
    }

    public function updateFromThePost(): void {

        ////////////////////
        // Carry out Actions
        ////////////////////
        foreach ($_GET as $key => $value) {
            list($humanName, $targetID, $action) = explode('@', $key);

            switch ($action) {
                case ButtonOperationTypes::prependStep:
                    $target = $this->findByID($targetID);
                    if ($target) {
                        $target->prependStep(new WorkoutStep('prepended step'));
                    }
                    break;

                case ButtonOperationTypes::appendStep:
                    $target = $this->findByID($targetID);
                    if ($target) {
                        $target->appendStep(new WorkoutStep('appended step'));
                    }
                    break;

                case ButtonOperationTypes::appendWorkout:
                    $target = $this->findByID($targetID);
                    if ($target) {
                        $target->appendWorkout(new Workout(2));
                    }
                    break;

                case ButtonOperationTypes::deleteStep:
                    $child = $this->findByID($targetID);
                    if ($child) {
                        $parent = $this->findParentOf($child);
                        if ($parent) {
                            $parent->deleteStep($child);
                        }
                    }
                    break;

                case ButtonOperationTypes::moveUp:
                    $child = $this->findByID($targetID);
                    if ($child) {
                        $parent = $this->findParentOf($child);
                        if ($parent) {
                            $parent->moveUp($child);
                        }
                    }
                    break;

                case ButtonOperationTypes::moveDown:
                    $child = $this->findByID($targetID);
                    if ($child) {
                        $parent = $this->findParentOf($child);
                        if ($parent) {
                            $parent->moveDown($child);
                        }
                    }
                    break;

                case ButtonOperationTypes::edit:
                    $step = $this->findByID($targetID);
                    if ($step) {
                        $this->clearBeingEdited();
                        $step->beingEdited = true;
                    }
                    break;

                case ButtonOperationTypes::ok:
                    $this->clearBeingEdited();
                    break;

                case ButtonOperationTypes::updateStepCaption:
                    $step = $this->findByID($targetID);
                    if ($step){
                        $step->caption = $value;
                    }
                    break;

                default:
                    break;
            }
        }
    }
}

//////////////////////////////////////////////////
// load the workout or build the workout if needed
//////////////////////////////////////////////////


$serialised = file_get_contents('workout');

if ($serialised) {
    $mainWorkout = unserialize($serialised);
} else {
    $mainWorkout = new Workout();
    /*
      $subWorkout = new Workout(5);
      $subWorkout->appendStep(new WorkoutStep('work'));
      $subWorkout->appendStep(new WorkoutStep('recover'));

      $mainWorkout->appendStep(new WorkoutStep('warm up'));
      $mainWorkout->appendWorkout($subWorkout);
      $mainWorkout->appendStep(new WorkoutStep('cool down'));
     * 
     */
    
}


//////////////////////////////////////////////
// carry out any operations present in the GET
// ///////////////////////////////////////////

$mainWorkout->updateFromThePost();

///////////////////////////////////
// make the page including the form
///////////////////////////////////

list($html, $head, $body) = makePage('TESTING');

$form = $body->makeChild('form');

$form->addChild($mainWorkout->makeTags());

///////////////////////////
// finally, echo the result
///////////////////////////

$html->echo();
print_r($_GET);

////////////////////////////
// write back to the session
////////////////////////////

$serialised = serialize($mainWorkout);
file_put_contents('workout', $serialised);

//$_SESSION['workout'] = $mainWorkout;

/*
Tag::make('script',
    '
     
        
        window.pageYoffset = sessionStorage.getItem("scrollPos"); 
        
        alert(sessionStorage.getItem("scrollPos"));
        
        onsubmit = (event) => {
            sessionStorage.setItem("scrollPos", window.pageYoffset);
            alert("post");
        };
    '
)->echo();
*/

