<?php
//include_once  '../../Common/PHP/all.php';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// buttons should not process the post until the DOM is constructed as they might trigger actions upon the DOM structure
// 
// input fields should so that changes in the posted value are reflected in the elements of the DOM
// 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class ButtonPool {

    public static array $fields = [];

    static function add(BaseField $field): void {
        self::$fields[] = $field;
    }

    static function triggerButtons(): void {
        foreach (self::$fields as $field) {
            $field->processPost();
        }
    }
}

class ButtonField extends BaseField {

    public string $caption;
    public string $value;
    public array $objectMethodSignature = [];

    public function __construct(string $caption = 'AnonBtn', string $target = 'no_target', array $objectMethodSignature = []) {
        parent::__construct();
        $this->fieldID = implode('@', [uniqid(), 'button', 'submit']);
        $this->caption = $caption;
        $this->value = $target;
        $this->objectMethodSignature = $objectMethodSignature;
        //ButtonPool::add($this);
    }

    public function processPost(): void {
        $target = $this->getValueFromPost();
        if ($target) {
            if (sizeof($this->objectMethodSignature)) {
                call_user_func($this->objectMethodSignature, $target);
            }
        }
    }

    public function makeTags(): Tag {
        // buttons should not process the post until the DOM is constructed as they might trigger actions upon the DOM
        // input fields should so that changes in the posted value are reflected in the DOM

        return Tag::make('button', $this->caption, ['id' => $this->fieldID, 'name' => $this->fieldID, 'type' => 'submit', 'value' => $this->value]);
    }

    public function testCallback(string $target) {
        echo "<h1>call back $target</h1>";
    }
}

class SelectField extends BaseField {

    public string $value;
    public array $options;

    public function __construct($options, $value = '') {
        parent::__construct();
        $this->value = $value;
        $this->options = $options;
        $this->fieldID = implode('@', [uniqid(), 'select', 'options']);
    }

    public function processPost(): void {
        $postedValue = $this->getValueFromPost();
        if ($postedValue) {
            $this->value = $postedValue;
        }
    }
    
    public function toString():string{
        return $this->value;
    }

    public function makeTags(): Tag {
        $this->processPost();
        $select = Tag::make('select', '', ['id' => $this->fieldID, 'name' => $this->fieldID, 'type' => 'text', 'value' => $this->value]);
        foreach ($this->options as $option) {
            if ($this->value == $option) {
                $select->makeChild('option', $option, ['selected' => $option]);
            } else {
                $select->makeChild('option', $option);
            }
        }
        return $select;
    }
}

class TextField extends BaseField {

    public string $value;
    public $checkVal;

    public function __construct(string $value = '', ?CheckValue $checkVal = null) {
        parent::__construct();
        $this->value = $value;
        $this->fieldID = implode('@', [uniqid(), 'input', 'text']);
        $this->checkVal = $checkVal;
    }

    public function processPost(): void {
        $postedValue = $this->getValueFromPost();
        if ($postedValue) {
            if ($this->checkVal != null) {
                if ($this->checkVal->check($postedValue)) {
                    $this->value = $postedValue;
                }
            } else {
                $this->value = $postedValue; // unchecked
            }
        }
    }

    public function makeTags(): Tag {
        $this->processPost();
        $input = Tag::make('input', '', ['id' => $this->fieldID, 'name' => $this->fieldID, 'type' => 'text', 'value' => $this->value]);
        if ($this->checkVal != null) {
            if ($this->checkVal->inError()) {
                $input->makeChild('div', '* ' . $this->checkVal->errorMessage(), ['style'=>'color:red;']);
            }
        }
        return $input;
    }
}

class LabeledTextField extends TextField {

    public string $caption;

    public function __construct(string $caption, string $value = '', ?CheckValue $checkVal = null) {
        parent::__construct($value, $checkVal);
        $this->caption = $caption;
    }

    public function makeTags(): Tag {
        $div = Tag::make('div');
        $div->makeChild('label', $this->caption);
        $div->addChild(parent::makeTags());
        return $div;
    }
}

class BaseField {

    public string $fieldID;

    public function __construct() {
        $this->fieldID = implode('@', [uniqid(), 'BaseField']);
    }

    /////////////////////////////////////////////////////////////////////
    // THIS IS THE OUTPUT OF THE FIELD, EVENTUALLY VIA ECHO TO THE 'POST'
    /////////////////////////////////////////////////////////////////////

    //abstract function makeTags(): Tag;

    /////////////////////////////////////////////////////////
    // THIS IS THE INPUT TO THE FIELD VIA THE 'POST' or 'GET'
    /////////////////////////////////////////////////////////


    public function getValueFromPost($filter = FILTER_SANITIZE_SPECIAL_CHARS) {
        if (isset($_GET[$this->fieldID])) {
            return filter_var($_GET[$this->fieldID], $filter);
        }
        return null;
    }

    //abstract function processPost(): void;
}



