
<!DOCTYPE html>

<html>
    <head>
        <meta charset = "UTF-8">
        <title>Drew's Workouts</title>
        <link rel = "stylesheet" type = "text/css" href = "../CSS/mystyle.css?version = 2312" />
    </head>
    <body>

        <?php
        session_start();
        require_once 'HTML.php';

// THE INTERNAL REPRESENTATION OF DURATION IS EITHER (TIME IN SECONDS,  DISTANCE IN METERS)
// THE INTERNAL REPRESENTATION OF INTENSITY IS EITHER (HR IN PER SECS, SPEED IN METERS/SECOND, POWER IN WATTS)
// USER CONFIGURATION WILL DETERMINE HOW THESE ARE DISPLAYED

        /*
         *      THIS IS THE MODEL
         */

        class DURATIOM {

            public static $SECONDS = 1;
            public static $METERS = 2;

        }

        class INTENSITY {

            public static $HR = 1;
            public static $SPEED = 2;
            public static $POWER = 3;

        }

        class Phase extends Tags {

            // member controls
            // input fields
            protected $mPhaseTitleControl;
            protected $mSelectDurationType;
            protected $mInputDuration;
            protected $mSelectIntensityType;
            protected $mInputIntensityHigh;
            protected $mInputIntensityLow;
            // buttons
            protected $mMoveUpButton;
            protected $mMoveDownButton;
            protected $mDeleteButton;
            protected $mInsertPhaseAboveButton;
            protected $mInsertPhaseBelowButton;
            protected $mAddBlockButton;

            public function __construct($title = '', $durationType = 'time', $duration = '5:00', $intensityType = 'HR', $intensityLow = '100', $intensityHigh = '140') {

                parent::__construct('div', 'div');


                $this->applyCorrectPhaseClass($title);

                //$this->addBetween(new Heading('heading', 'h5', $title));
                // create display objects
                $table = new Table('', 4, 6);

                // Title
                $this->mPhaseTitleControl = new Select(uniqid('phase_title_'), ['WARM UP', 'WORK', 'RECOVER', 'COOL DOWN', ''], $title);
                $this->mPhaseTitleControl->addAttribute('onchange', 'requestPost()');
                $phaseTitleControlLabel = new Label('label', $this->mPhaseTitleControl->getID(), 'description');

                // Duration
                $this->mSelectDurationType = new Select(uniqid('duration_type_'), ['time', 'meters', 'kilometers', 'miles'], $durationType);

                $this->mInputDuration = new InputText(uniqid('duration_'), $duration);
                $this->mInputDuration->setType(INPUT_TYPES::$TIME);
                $inputDurationLabel = new Label('label', $this->mInputDuration->getID(), 'duration');

                // Intensity
                $this->mSelectIntensityType = new Select(uniqid('intensity_type_'), ['heart rate', 'kilometers per hour', 'miles per hour', 'minutes per kilometer', 'minutes per mile', 'power in Watts'], $intensityType);
                $labelIntensityType = new Label('label', $this->mSelectIntensityType->getID(), 'measure');
                $this->mInputIntensityHigh = new InputText(uniqid('intensity_high_'), $intensityHigh);
                $inputIntensityHighLabel = new Label('label', $this->mInputIntensityHigh->getID(), 'high');
                $this->mInputIntensityLow = new InputText(uniqid('intensity_low_'), $intensityLow);
                $inputIntensityLowLabel = new Label('label', $this->mInputIntensityLow->getID(), 'low');
                $labelIntensityTitle = new Label('Label', $this->mInputIntensityHigh->getID(), 'intensity');

                // Delete or Move Phase Buttons
                $this->mDeleteButton = new SubmitButton(uniqid('delete_'), '&#x2718 delete', 'delete');
                $this->mMoveDownButton = new SubmitButton(uniqid('move_down_'), '&#x2B07', 'move_down');
                $this->mMoveUpButton = new SubmitButton(uniqid('move_up_'), '&#x2B06', 'move_up');
                $this->mInsertPhaseAboveButton = new SubmitButton(uniqid('insert_phase_above_'), '&#x271A &#x2B06', 'insert_phase_above');
                $this->mInsertPhaseBelowButton = new SubmitButton(uniqid('insert_phase_below_'), '&#x271A &#x2B07', 'insert_phase_below');
                $this->mAddBlockButton = new SubmitButton(uniqid('add_'), '&#x27A5 repeats', 'add_block');


                // connect the objects together
                $this->addChild($table);

                $rowCursor = 0;
                $table->setItemAtRowCol($rowCursor, 0, $this->mMoveUpButton);
                $table->setItemAtRowCol($rowCursor, 1, $this->mInsertPhaseAboveButton);
                $table->setItemAtRowCol($rowCursor, 4, $labelIntensityTitle);
                $table->setItemAtRowCol($rowCursor, 5, $this->mDeleteButton);

                $rowCursor++;
                $table->setItemAtRowCol($rowCursor, 0, $phaseTitleControlLabel);
                $table->setItemAtRowCol($rowCursor, 1, $this->mPhaseTitleControl);
                $table->setItemAtRowCol($rowCursor, 3, $inputIntensityHighLabel);
                $table->setItemAtRowCol($rowCursor, 4, $this->mInputIntensityHigh);
                $table->setItemAtRowCol($rowCursor, 5, $labelIntensityType);


                $rowCursor++;
                $table->setItemAtRowCol($rowCursor, 0, $inputDurationLabel);
                $table->setItemAtRowCol($rowCursor, 1, $this->mInputDuration);
                $table->setItemAtRowCol($rowCursor, 2, $this->mSelectDurationType);
                $table->setItemAtRowCol($rowCursor, 3, $inputIntensityLowLabel);
                $table->setItemAtRowCol($rowCursor, 4, $this->mInputIntensityLow);
                $table->setItemAtRowCol($rowCursor, 5, $this->mSelectIntensityType);


                $rowCursor++;
                $table->setItemAtRowCol($rowCursor, 0, $this->mMoveDownButton);
                $table->setItemAtRowCol($rowCursor, 1, $this->mInsertPhaseBelowButton);
                $table->setItemAtRowCol($rowCursor, 5, $this->mAddBlockButton);
            }

            protected function applyCorrectPhaseClass($title) {
                $this->removeAttribute('class');
                switch ($title) {
                    case 'WARM UP':
                        $this->addAttribute('class', 'phase_warm_up');
                        break;
                    case 'COOL DOWN':
                        $this->addAttribute('class', 'phase_cool_down');
                        break;
                    case 'WORK':
                        $this->addAttribute('class', 'phase_work');
                        break;
                    case 'RECOVER':
                        $this->addAttribute('class', 'phase_recover');
                        break;
                    default:
                        $this->addAttribute('class', 'phase_default');
                        break;
                }
            }

            public function moveUp() {
                return $this->mMoveUpButton->pressed();
            }

            public function moveDown() {
                return $this->mMoveDownButton->pressed();
            }

            public function addPhaseBelow() {
                return $this->mInsertPhaseBelowButton->pressed();
            }

            public function addPhaseAbove() {
                return $this->mInsertPhaseAboveButton->pressed();
            }

            public function addBlockBelow() {
                return $this->mAddBlockButton->pressed();
            }

            public function delete() {
                return $this->mDeleteButton->pressed();
            }

            public function handleGETorPOST() {
                parent::handleGETorPOST();
                if ($this->mPhaseTitleControl->updated()) {
                    $this->applyCorrectPhaseClass($this->mPhaseTitleControl->getDomValue());
                }

                switch ($this->mSelectDurationType->getDomValue()) {
                    case 'time':
                        $this->mInputDuration->setType(INPUT_TYPES::$TIME);
                        break;
                    case 'meters':
                        $this->mInputDuration->setType(INPUT_TYPES::$INTEGER);
                        break;
                    case 'kilometers':case 'miles':
                        $this->mInputDuration->setType(INPUT_TYPES::$FLOAT);
                        break;
                    default:
                        $this->mInputDuration->setType(INPUT_TYPES::$ANYTHING);
                        break;
                }

                switch ($this->mSelectIntensityType->getDomValue()) {
                    case 'heart rate': case 'power in Watts':
                        $this->mInputIntensityHigh->setType(INPUT_TYPES::$INTEGER);
                        $this->mInputIntensityLow->setType(INPUT_TYPES::$INTEGER);
                        break;
                    case 'kilometers per hour': case 'miles per hour': case 'kilometers per hour': case 'minutes per kilometer': case 'minutes per mile':
                        $this->mInputIntensityHigh->setType(INPUT_TYPES::$FLOAT);
                        $this->mInputIntensityLow->setType(INPUT_TYPES::$FLOAT);
                        break;

                    default:
                        $this->mInputIntensityHigh->setType(INPUT_TYPES::$ANYTHING);
                        $this->mInputIntensityLow->setType(INPUT_TYPES::$ANYTHING);
                        break;
                }
            }

        }

        class Block extends Tags {

            protected $mDeleteBlockButton;

            public function __construct($iterations = '5', $phases = [], $isRoot = false) {
                parent::__construct('div', 'div');

                if ($isRoot) {
                    $this->addAttribute('class', 'blockatroot');
                } else {
                    $this->addAttribute('class', 'block');
                    $select = new Select(uniqid('iterations_'), ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'], $iterations);
                    $this->addChild($select, $iterations);
                    $this->addChild(new Label('label', $select->getID(), 'repeats'));
                    $this->mDeleteBlockButton = new SubmitButton(uniqid('delete_block_'), 'delete repeats');
                    $this->addChild($this->mDeleteBlockButton);
                }

                // if the array of phases is empty then spoof the default phases, work and recover
                if (count($phases) === 0) {
                    $phases = [new Phase('WORK'), new Phase('RECOVER')];
                }

                // add phases supplied as arguments in the array $phases to the block
                foreach ($phases as $phase) {
                    $this->addChild($phase);
                }
            }

            public function delete() {
                return $this->mDeleteBlockButton->pressed();
            }

            public function handleGETorPOST() {
                parent::handleGETorPOST();

                // handle messages for the inBetweeners of Block
                //foreach ($this->mInBetweeners as $item) {
                for ($i = 0; $i < count($this->mChildren); $i++) {
                    $item = $this->mChildren[$i];
                    if ($item instanceof Phase) {
                        if ($item->moveUp()) {
                            swapTypes($this->mChildren, $i, - 1, ['Phase', 'Block']);
                            return;
                        } else if ($item->moveDown()) {
                            swapTypes($this->mChildren, $i, + 1, ['Phase', 'Block']);
                            return;
                        } else if ($item->addPhaseAbove()) {
                            $this->insertChild($i, new Phase());
                            return;
                        } else if ($item->addPhaseBelow()) {
                            $this->insertChild($i + 1, new Phase());
                            return;
                        } else if ($item->addBlockBelow()) {
                            $this->insertChild($i + 1, new Block());
                            return;
                        } else if ($item->delete()) {
                            if ($this->childCount(['Phase']) > 1) {
                                $this->deleteChildAtIndex($i);
                            }
                            return;
                        }
                    } else if ($item instanceof Block) {
                        if ($item->delete()) {
                            $this->deleteChildAtIndex($i);
                        }
                    }
                }
            }

        }

        class WorkoutForm extends AutoPostingForm {

            public function __construct() {
                parent::__construct('WorkoutBuilder', true);
            }

            protected function build() {
                parent::build();

                $inputTitle = new InputText('workout_name', 'not named');
                $this->form = new Form($this->sessionKey, 'Workout Builder', 'GET', '', true);

                // building so just alighted on this page
                // check to see if workout id has been handed over
                $workoutID = GETorPOST::getValueWithKey('SelectedWorkoutID');

                if ($workoutID !== null) {
                    Database::connect();
                    $SQL = 'SELECT * FROM workouts WHERE id = :id';
                    $preparedStatement = Database::prepare($SQL);
                    $preparedStatement->execute([':id' => $workoutID]);
                    $recordsFound = $preparedStatement->fetchAll();
                    if (count($recordsFound) === 1) {
                        scream('found');
                        $this->form->addChild($recordsFound[0]['workout']);
                    } else {
                        $this->buildDefaultForm();
                    }
                } else {
                    $this->buildDefaultForm();
                }
            }

            protected function buildDefaultForm() {
                $warmUp = new Phase('WARM UP', DURATIOM::$SECONDS, 5 * 60, INTENSITY::$HR, 120, 140);
                $coolDown = new Phase('COOL DOWN', DURATIOM::$SECONDS, 5 * 60, INTENSITY::$HR, 120, 140);
                $fast = new Phase('WORK', DURATIOM::$SECONDS, 4 * 60, INTENSITY::$HR, 140, 170);
                $recover = new Phase('RECOVER', DURATIOM::$SECONDS, 1 * 60, INTENSITY::$HR, 120, 140);

                $intervals = new Block('5', [$fast, $recover]);

                $workout = new Block(1, [$warmUp, $intervals, $coolDown], true);

                $this->form->addChild($inputTitle);
                $this->form->addChild($workout);

                $this->form->addChild(new SubmitButton('saveButton', 'save', '100'));
            }

            protected function loadFormFromSession() {
                parent::loadFormFromSession();
            }

            protected function storeFormInSession() {
                parent::storeFormInSession();
            }

            protected function handleGET() {
                parent::handleGet();

                /*
                  echo GetPost::verb();
                  echo '<br>';
                  print_r($_GET);
                  print_r($_POST);
                 * 
                 */
            }

        }

        $form = new WorkoutForm();
        echo $form->toString();




        //$format = '%d  %02d';
        //$pattern = '/(%0?\d?d)*/';
        /*
          echo '<br><br>';
          echo preg_match($pattern, $format, $matches);
          echo '<br><br>';
          echo count($matches);
         */

        /*
          $value = '  1 meters';
          scream($value);
          $pattern = '/^\s*(\d+)\s*m?[a-zA-Z]*$/';
          $format = '%d';
          if (preg_match($pattern, $value, $matches)) {
          print_r($matches);
          if (isset($matches[1])) {
          $meters = intval($matches[1]);
          scream(sprintf($format, $meters));
          }
          }
         * */

        print_r($_GET);
        ?>


    </body>




</html>


