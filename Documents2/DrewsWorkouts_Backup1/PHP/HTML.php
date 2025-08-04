<?php

require_once 'constants.php';
require_once 'tools.php';



/*
 *      Session Wrapper Static Class
 */

class Session {

    public static function setValueWithKey($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function getValueWithKey($key, $defaults_to = null) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return $defaults_to;
        }
    }

    public static function setLoggedIn(bool $value) {
        Session::setValueWithKey(LOGGED_IN_SESSION_KEY, $value);
    }

    public static function loggedIn() {
        return Session::getValueWithKey(LOGGED_IN_SESSION_KEY);
    }

    public static function setUserID(int $value) {
        Session::setValueWithKey(USER_ID_SESSION_KEY, $value);
    }

    public static function userID() {
        return Session::getValueWithKey(USER_ID_SESSION_KEY);
    }

    public static function setUserName(string $value) {
        Session::setValueWithKey(USERNAME_SESSION_KEY, $value);
    }

    public static function userName() {
        return Session::getValueWithKey(USERNAME_SESSION_KEY);
    }

    public static function setSelectedDay(int $value) {
        Session::setValueWithKey(SELECTED_DAY_SESSION_KEY, $value);
    }

    public static function selectedDay() {
        return Session::getValueWithKey(SELECTED_DAY_SESSION_KEY);
    }

    public static function toString() {
        return "<br><br>" .
                "UserID: " . Session::userID() . "<br>" .
                "Username: " . Session::userName() . "<br>" .
                "Logged in: " . Session::loggedIn() . "<br>" .
                "Selected day: " . Session::selectedDay() . "<br>";
    }

}

class GetPost {

    public static function getValueWithKey($key, $default = null) {

        $result = $default;

        if (GetPost::verb() === 'GET') {
            if (isset($_GET[$key])) {
                $result = $_GET[$key];
            }
        } else if (GetPost::verb() === 'POST') {
            if (isset($_POST[$key])) {
                $result = $_POST[$key];
            }
        }

        return $result;
    }

    public static function verb() {
        if ($_SERVER["REQUEST_METHOD"] === 'GET') {
            return 'GET';
        } else if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            return 'POST';
        } else {
            return '';
        }
    }

}

/*
 * Database Wrapper Static Class
 */

class Database {

    protected static $PDO = null;

    public static function connect() {
        try {
            self::$PDO = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
// Set the PDO error mode to exception
            self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("ERROR: Could not connect. " . $e->getMessage());
        }
    }

    public static function prepare($sql) {
        return self::$PDO->prepare($sql);
    }

    public static function PDO() {
        return self::$PDO;
    }

}

/*
 * Start of the HTML generating PHP objects, simplest being Tag (single) and Tags(opening and closing tags) which inherits from Tag
 */

class Tag {

    protected $mObjectIdentifier = '';
    protected $mAssociatedValue = null;
    protected $mPreviousAssociatedValue = null;
    protected $mUpdatedFromGetPost = false;
    protected $mChanged = false;
    protected $mTypeName = '';
    protected $mInsideTag = [];

    public function __construct($objectIdentifier, $typeName) {

        $this->mObjectIdentifier = $objectIdentifier === '' ? $typeName . '_' . uniqid() : $objectIdentifier;
        $this->mTypeName = $typeName;
        $this->addInside('name', $this->mObjectIdentifier); // name IS associated with POST data [name]=>data
        $this->addInside('id', $this->mObjectIdentifier);
    }

    public function addInside($key, $value) {
        $this->mInsideTag[$key] = $value;
    }

    public function removeInside($key) {
        unset($this->mInsideTag[$key]);
    }

    public function clearInside() {
        $this->mInsideTag = [];
    }

    public function &getByName($name) {

        $result = null;
        if ($this->mObjectIdentifier === $name) {
            $result = $this;
        } else if (is_a($this, 'Tags')) {
            foreach ($this->mInBetweeners as $child) {
                if (is_a($child, 'Tag')) {
                    $got = $child->getByName($name);
                    if ($got != null) {
                        $result = $got;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    public function handleGetPost() {
        $this->mUpdatedFromGetPost = false;
        $this->mChanged = false;
        $postedValue = GetPost::getValueWithKey($this->mObjectIdentifier, null);
        if ($postedValue !== null) {
            $this->mUpdatedFromGetPost = true;
            if ($this->mAssociatedValue !== $postedValue) {
                $this->mAssociatedValue = $postedValue;
                $this->mChanged = true;
                $this->mPreviousAssociatedValue = $this->mAssociatedValue;
            }
        }
    }

    public function getID() {
        return $this->mObjectIdentifier;
    }

    public function updated() {  // may still be the same
        return $this->mUpdatedFromGetPost;
    }

    public function changed() {
        return $this->mChanged;
    }

    public function getPreviousValue() {
        return $this->mPreviousAssociatedValue;
    }

    public function getValue() {
        $postedValue = GetPost::getValueWithKey($this->mObjectIdentifier, null);
        if ($postedValue !== null) {
            return $postedValue;
        } else {
            return $this->mAssociatedValue;
        }
    }

    public function toString($tab = TAB8) {
        $insideTagStr = '';
        foreach ($this->mInsideTag as $key => $value) {
            $insideTagStr .= ' ' . $key . '="' . $value . '"';
        }
        $result = $tab . "<" . $this->mTypeName . $insideTagStr . ">\n";
        return $result;
    }

}

class Tags extends Tag {

    protected $mInBetweeners = array();

    public function __construct($objectIdentifier, $typeName) {
        parent::__construct($objectIdentifier, $typeName);
    }

    public function setBetween($textOrTag) {
        $this->mInBetweeners = [$textOrTag];
    }

    public function addBetween($textOrTag) {
        $this->mInBetweeners[] = $textOrTag;
    }

    public function betweenCount($classNames = []) {
        if (count($classNames) === 0) {
            return count($this->mInBetweeners);
        } else {
            $count = 0;
            foreach ($this->mInBetweeners as $item) {
                if (array_search(get_class($item), $classNames) !== false) {
                    $count++;
                }
            }
            return $count;
        }
    }

    public function insertBetween($index, $textOrTag) {
        array_splice($this->mInBetweeners, $index, 0, [$textOrTag]);
    }

    public function deleteBetween($index) {
        //$this->mInBetweeners[$index] = '<h3>deleted</h3>';
        array_splice($this->mInBetweeners, $index, 1);
    }

    public function clearBetween() {
        $this->mInBetweeners = [];
    }

// returns a reference to the stored item in mInBetweeners

    public function &getBetween($index) {
        if ($index < 0 || $index >= count($this->mInBetweeners)) {
            throw new Exception('///// DREW ///// - Range check error in Tags::&getBetween($index)');
        }
        return $this->mInBetweeners[$index];
    }

    public function handleGetPost() {
        parent::handleGetPost();
        foreach ($this->mInBetweeners as $item) {
            if ($item instanceof Tag) {
                $item->handleGetPost();
            }
        }
    }

    public function toString($tab = TAB8) {
        $result = parent::toString($tab);
        foreach ($this->mInBetweeners as $item) {
            if ($item instanceof Tag) {
                $result .= $item->toString($tab . TAB4);
            } else if (is_string($item)) {
                $result .= $tab . TAB4 . $item . "\n";
            }
        }
        $result .= $tab . '</' . $this->mTypeName . ">\n";
        return $result;
    }

}

class Heading extends Tags {

    public function __construct($objectIdentifier, $typeName, $heading) {
        parent::__construct($objectIdentifier, $typeName);
        $this->addBetween($heading);
    }

}

class Option extends Tags {

    public function __construct($objectIdentifier, $value) {
        parent::__construct($objectIdentifier, 'option');
        $this->addInside('value', $value);
        $this->addBetween($value);
    }

}

class Select extends Tags {

    protected $options;

    public function __construct($objectIdentifier, $options, $defaultOption = '') {
        parent::__construct($objectIdentifier, 'select');
        $this->options = $options;
        $this->mAssociatedValue = $defaultOption;
        $this->addInside('onchange', 'requestPost()');
        $this->build();
    }

    public function handleGetPost() {
        parent::handleGetPost();
        $this->build();
    }

    protected function build() {
        $this->clearBetween();
        foreach ($this->options as $option) {
            $optionTag = new Option('option', $option);
            if ($option === $this->mAssociatedValue) {
                $optionTag->addInside('selected', 'selected');
            }
            $this->addBetween($optionTag);
        }
    }

}

class INPUT_TYPES {

    public static $ANYTHING = '0';
    public static $TIME = '1';
    public static $INTEGER = '2';
    public static $FLOAT = '3';

}

class InputText extends Tags {

    protected $mErrorLabel;
    protected $mInputType;

    public function __construct($objectIdentifier, $defaultValue = '') {
        parent::__construct($objectIdentifier, 'input');
        $this->mErrorLabel = new Label('label', $this->getID(), '');
        $this->addBetween($this->mErrorLabel);
        $this->mAssociatedValue = $defaultValue;
        $this->addInside('type', 'text');
        $this->addInside('value', $defaultValue);
        $this->addInside('onfocusout', 'requestPost()');
    }

    public function setType($type) {
        $this->mInputType = $type;
        $this->refreshErrorState();
    }

    protected function refreshErrorState() {
        $value = $this->getValue();
        $validValue = $this->validateValue($value);
        if ($validValue === null) {
            $this->addInside('value', $value);
            $this->setError('invalid format');
        } else {
            $this->addInside('value', $validValue);  // add the raw invalid value to the control
            $this->clearError();
        }
    }

    public function handleGetPost() {
        parent::handleGetPost();
        $this->refreshErrorState();
    }

    protected function setError($msg) {
        $this->mErrorLabel->setBetween('?');
        $this->mErrorLabel->addInside('class', 'error');
        $labelMsg = new Label('label', $this->mErrorLabel->getID(), $msg);
        $labelMsg->addInside('class', 'error_message');
        $this->mErrorLabel->addBetween($labelMsg);
    }

    protected function clearError() {
        $this->mErrorLabel->clearBetween();
        $this->mErrorLabel->clearInside();
    }

    protected function validateValue($value) {
        // check if value is a valid format
        // convert to standard format
        // return standard format value if possible
        // flag up error if unable to do so as not a valid format

        $result = null;
        $matches = [];

        switch ($this->mInputType) {

            case INPUT_TYPES::$TIME:
                $pattern = '/^((\d{1,2}):)?(\d{1,2})(\.(\d{1,2}))?$/';
                $format2 = "%d.%02d";
                $format3 = '%d:%02d.%02d';
                $hrs = 0;
                $mins = 0;
                $secs = 0;
                if (preg_match($pattern, $value, $matches)) {
                    $hrs = $matches[2] === '' ? 0 : intval($matches[2]);    // must exist though may be === ''
                    $mins = intval($matches[3]);                            // must exist in matches and !== ''
                    if (isset($matches[5])) {                                // may not exist
                        if ($matches[5] !== '') {                            // and may be === ''
                            $secs = intval($matches[5]);
                        }
                    }
                    if ($hrs === 0) {
                        $result = sprintf($format2, $mins, $secs);
                    } else {
                        $result = sprintf($format3, $hrs, $mins, $secs);
                    }
                }
                break;

            case INPUT_TYPES::$INTEGER:
                $pattern = '/^\s*(\d*(\.\d+)?)\s*$/';
                $format = '%d';
                if (preg_match($pattern, $value, $matches)) {
                    //print_r($matches);
                    if (isset($matches[1])) {
                        $meters = intval($matches[1]);
                        $result = sprintf($format, $meters);
                    }
                }
                break;

            case INPUT_TYPES::$FLOAT:
                $pattern = '/^\s*(\d*(\.\d+)?)\s*$/';
                $format = '%0.2f';
                if (preg_match($pattern, $value, $matches)) {
                    //print_r($matches);
                    if (isset($matches[1])) {
                        $distance = floatval($matches[1]);
                        $result = sprintf($format, $distance);
                    }
                }
                break;

            default:
                $result = $value;
                break;
        }
        return $result;
    }

}

/* this adds a hidden input control to the form and ensures it has a fresh
 * value each time.  this ensures that a GET is always posted as the get
 * string changes.  
 */

class HiddenInputGetPostChanger extends Tag {

    public function __construct() {
        parent::__construct(uniqid('hidden_input_'), 'input');
        $this->mAssociatedValue = 0;
        $this->addInside('type', 'hidden');
    }

    public function handleGetPost() {
        parent::handleGetPost();
        $this->addInside('value', intval($this->getValue()) + 1);
    }

}

class Form extends Tags {

    protected $mTitle = '';
    protected $mScript = '';
    protected $mGetPostChanger;

    //protected $mPageNumber = 0;

    public function __construct($objectIdentifier, $title = '', $verb = 'POST', $action = '', $postOnChange = false) {
        parent::__construct($objectIdentifier, 'form');
        $this->mTitle = $title;
        $this->addInside('method', $verb);
        $this->mGetPostChanger = new HiddenInputGetPostChanger();
        $this->addBetween($this->mGetPostChanger);
        $this->mScript = new Tags('script', 'script');
        if ($postOnChange) {
            $this->mScript->addBetween(
                    "
            var gNeedsPost = false;

            window.setInterval(postOnRequest, 100);

            function postOnRequest(){
                if (gNeedsPost){
                    gNeedsPost = false;
                    document.getElementById('workoutBuilderForm').submit();
                }
            }

            function requestPost(){
                 gNeedsPost = true;
                 //window.alert('set to update');
            }
            "
            );
        }
    }

    public function changeInPageNumber() {
        $previous = $this->mGetPostChanger->getPreviousValue();
        $current = $this->mGetPostChanger->getValue();
        return intval($current) - intval($previous);
    }

    public function pageNumber() {
        return $this->mGetPostChanger->getValue();
    }

    /*
      public function addBreak() {
      $this->addBetween("\n<br>\n");
      }
     * 
     */

    /*
      public function handleGetPost() {
      if (!$this->mCounter->decrementing()) {
      parent::handleGetPost();
      }
      }
     * 
     */

    /*
      public function incrementPageNumber(){
      $this->mPageNumber++;
      scream($this->mPageNumber);
      }
     * 
     */

    public function toString($tab = TAB8): string {
        $result = "\n<!-- START OF GENERATED HTML CODE PRODUCED BY DREW'S PHP FRAMEWORK -->\n";
        $result .= "\n" . $tab . '<h2>' . $this->mTitle . "</h2>\n";
        $result .= parent::toString($tab = TAB8);
        $result .= $this->mScript->toString($tab = TAB8);
        $result .= "\n<!-- END OF GENERATED HTML CODE PRODUCED BY DREW'S PHP FRAMEWORK -->\n\n";
        return $result;
    }

}

class Table extends Tags {

    public function __construct($objectIdentifier, $rows, $cols) {
        parent::__construct($objectIdentifier, 'table');
        for ($row = 0; $row < $rows; $row++) {
            $rowIdentifier = "row_" . $row;
            $newRow = new Tags($rowIdentifier, 'tr');
            for ($col = 0; $col < $cols; $col++) {
                $itemIdentifier = 'col_' . $col;
                $newData = new Tags($itemIdentifier, 'td');
                //$newData->addBetween('');
                $newRow->addBetween($newData);
            }
            $this->addBetween($newRow);
        }
    }

    public function setItemAtRowCol($row, $col, $item) {
        $tableRow = & $this->getBetween($row);
        $tableData = & $tableRow->getBetween($col);
        $tableData->addBetween($item);
    }

}

class Label extends Tags {

    public function __construct($objectIdentifier, $inputID, $caption) {
        parent::__construct($objectIdentifier, 'label');
        $this->addInside('for', $inputID);
        $this->addBetween($caption);
    }

}

class SubmitButton extends Tags {

    public function __construct($objectIdentifier, $caption, $value = 'default') {
        parent::__construct($objectIdentifier, 'button');
        $this->addInside('value', $value);
        $this->addBetween($caption);
    }

    public function pressed() {
        return $this->mUpdatedFromGetPost;
    }

    public function value() {
        return $this->mAssociatedValue;
    }

}

/*
 * 
 * 
 * Higher Level Encapsulation
 * 
 * 
 */

class AutoPostingForm {

    protected $form = null;
    protected $sessionKey = null;
    protected $persistent = false;
    protected $oldVerb = '';

    public function __construct($sessionKey, $persistent = false) {
        $this->sessionKey = $sessionKey;
        $this->persistent = $persistent;

        if ($persistent) {
            $this->loadFormFromSession();
            if ($this->form === null) {
                $this->build();
            }
        } else {
            $this->build();
        }

        scream($this->form->changeInPageNumber());

        if (GetPost::verb() == 'POST') {
            $this->handlePOST();
        } else if (GetPost::verb() == 'GET') {
            $this->handleGET();
            //scream('get');
        }
    }

    public function __destruct() {
        $this->storeFormInSession();
    }

    protected function handleGET() {
        $this->form->handleGetPost(); // request DOM to update based on the GET or POST
    }

    protected function handlePOST() {
        $this->form->handleGetPost();
    }

    protected function build() {
        echo '<h3>new build</h3>';
    }

    private function storeFormInSession() {
        $_SESSION['PageIndex'] = 
        $_SESSION[$this->sessionKey] = serialize($this->form);
        scream('store '.$this->form->pageNumber());

    }

    private function loadFormFromSession() {

        if (isset($_SESSION[$this->sessionKey])) {
            $this->form = unserialize($_SESSION[$this->sessionKey]);
            scream('load '.$this->form->pageNumber());
           
        }
    }

    public function toString($tab = TAB8) {
        return $this->form->toString($tab = TAB8);
    }

}

class LoginForm extends AutoPostingForm {

    protected $PDO = null;
    protected $loggedIn = false;

    protected function handlePOST() {
        parent::handlePOST();


// get handles to the form objects
        $table = & $this->form->getByName('table');
        $usernameInput = & $this->form->getByName('usernameInput');
        $passwordInput = & $this->form->getByName('passwordInput');

// deal with the posted data
        $usernameTry = $usernameInput->getValue();
        $passwordTry = $passwordInput->getValue();

        $ballcarry = true;

        if ($ballcarry) {
            if ($usernameTry === '') {
                $usernameInput->setErrorMsg('enter username');
                $table->setItemAtRowCol(2, 1, 'error(s) on form');
                $ballcarry = false;
            }

            if ($passwordTry === '') {
                $passwordInput->setErrorMsg('enter password');
                $table->setItemAtRowCol(2, 1, 'error(s) on form');
                $ballcarry = false;
            }
        }

        if ($ballcarry) {
            Database::connect();
            $SQL = 'SELECT id, email, password FROM users WHERE email = :email';
            $preparedStatement = Database::prepare($SQL);
            $preparedStatement->execute(array(':email' => $usernameTry));
            $recordsFound = $preparedStatement->fetchAll();

            if (count($recordsFound) === 1) {
                $hash = $recordsFound[0]['password'];
                $userID = $recordsFound[0]['id'];
                if (password_verify($passwordTry, $hash)) {
                    $ballcarry = true;
                } else {
                    $table->setItemAtRowCol(1, 2, 'password is incorrect');
                    $ballcarry = false;
                }
            } else {
                $table->setItemAtRowCol(0, 2, 'username is not registered.');
                $ballcarry = false;
            }
        }

        if ($ballcarry) {
            $this->loggedIn = true;
            Session::setUserID($userID);
            Session::setUserName($usernameTry);
            Session::setLoggedIn(true);
        } else {
            $this->loggedIn = false;
            Session::setUserID(-1);
            Session::setUserName('No user logged in');
            Session::setLoggedIn(false);
        }
    }

    protected function build() {

        $form = new Form('form', 'LOG IN');
        $table = new Table('table', 3, 3);
        $form->addBetween($table);

        $inputUsername = new InputText('usernameInput');
        $table->setItemAtRowCol(0, 1, $inputUsername);
        //$table->setItemAtRowCol(0, 0, $inputUsername->createLabel('userNameLabel', 'User Name:'));
        $table->setItemAtRowCol(0, 0, new Label('label', $inputUsername->getID()), 'User Name');


        $inputPassword = new InputText('passwordInput');
        $table->setItemAtRowCol(1, 1, $inputPassword);
        //$table->setItemAtRowCol(1, 0, $inputPassword->createLabel('passwordLabel', 'Password:'));
        $table->setItemAtRowCol(1, 0, new Label('label', $inputPassword->getID(), 'Password'));

        $submitButton = new SubmitButton('submitButton', 'OK', 'SubmitButtonValue');
        $table->setItemAtRowCol(2, 0, $submitButton);

        $this->form = $form;
    }

    public function loggedIn() {
        return $this->loggedIn;
    }

}

class OptionsForm extends AutoPostingForm {

    protected $options = null;
    protected $choice = null;

    public function __construct($options) {
        $this->options = $options;
        parent::__construct('optionsForm');
    }

    protected function handleGET() {
        parent::handleGET();
        $option = GetPost::getValueWithKey('option');
        if ($option != null) {
            $this->choice = $option;
        }
    }

    protected function build() {
        $this->form = new Form('Options', 'Options', 'GET');
        $numberOfOptions = count($this->options);
        $table = new Table('table', $numberOfOptions, 1);
        $this->form->addInside('class', 'optionForm');
        $this->form->addBetween($table);

        $row = 0;
        foreach ($this->options as $option) {
            $button = new SubmitButton('option', $option, $option);
            $table->setItemAtRowCol($row, 0, $button);
            $row++;
        }
    }

    public function getChoice() {
        return $this->choice;
    }

}

class RegistrationForm extends AutoPostingForm {

    protected $PDO = null;
    protected $registered = false;

    public function __construct() {
        parent::__construct('registrationForm');
    }

    protected function build() {
        $form = new Form('registrationForm', 'REGISTER');
        $table = new Table('table', 3, 2);
        $form->addBetween($table);

        $inputUsername = new InputText('usernameInput');
        $table->setItemAtRowCol(0, 1, $inputUsername);
        $table->setItemAtRowCol(0, 0, new Label('label', $inputUsername->getID()), 'User Name');

        $inputPassword = new InputText('passwordInput');
        $table->setItemAtRowCol(1, 1, $inputPassword);
        $table->setItemAtRowCol(1, 0, new Label('label', $inputPassword->getID(), 'Password'));

        $submitButton = new SubmitButton('submitButton', 'OK', 'SubmitButtonValue');
        $table->setItemAtRowCol(2, 0, $submitButton);

        $this->form = $form;
    }

    protected function handlePost() {

        parent::handlePOST($verb);

// recreate the form from serialization in session then add any error information if needed
        $registered = false;

// get interface objects by name
        $table = & $this->form->getByName('table');
        $usernameInput = & $this->form->getByName('usernameInput');
        $passwordInput = & $this->form->getByName('passwordInput');

// get posted values
        $username = $usernameInput->getValue();
        $password = $passwordInput->getValue();

        $ballcarry = true;

        if ($username === '') {
            $usernameInput->setErrorMsg('enter username');
            $table->setItemAtRowCol(2, 1, 'error(s) on form');
            $ballcarry = false;
        }

        if ($password === '') {
            $passwordInput->setErrorMsg('enter username');
            $table->setItemAtRowCol(2, 1, 'error(s) on form');
            $ballcarry = false;
        }

        if ($ballcarry) {

            Database::connect();
            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = Database::prepare("insert into users set email = :email, password = :password");
                $stmt->execute(array(':email' => $username, ':password' => $hash));
                $this->registered = true;
//echo '<h1>registered</h1>debug';
            } catch (PDOException $e) {
                $this->registered = false;
            }
        }
    }

    public function registered() {
        return $this->registered;
    }

}

class CalendarTable extends Table {

    protected $startDay = null;
    protected $selectedDay = null;

    public function __construct($name) {

        parent::__construct($name, 7, 8);

        $unixToday = intdiv(time(), SECONDS_IN_A_DAY);
        $unixDayOfWeek = ($unixToday + 3) % 7;

        $this->startDay = $unixToday - $unixDayOfWeek;
        $this->selectedDay = $unixToday;
        $this->build();
        Session::setSelectedDay($this->selectedDay);
    }

    public function build() {

        $this->addInside('class', 'calendarTable');

        $dayRow = 1;
        $this->setItemAtRowCol($dayRow, 0, 'Mon');
        $this->setItemAtRowCol($dayRow, 1, 'Tue');
        $this->setItemAtRowCol($dayRow, 2, 'Wed');
        $this->setItemAtRowCol($dayRow, 3, 'Thu');
        $this->setItemAtRowCol($dayRow, 4, 'Fri');
        $this->setItemAtRowCol($dayRow, 5, 'Sat');
        $this->setItemAtRowCol($dayRow, 6, 'Sun');

        $this->setItemAtRowCol(0, 7, new SubmitButton(CALENDAR_SCROLL_BY_POST_KEY, '&#x21E7;', $this->startDay - 7));
        $this->setItemAtRowCol(6, 7, new SubmitButton(CALENDAR_SCROLL_BY_POST_KEY, '&#x21E9;', $this->startDay + 7));


// date stuff
        $unixToday = intdiv(time(), SECONDS_IN_A_DAY);
        $unixYear = date('Y', $this->selectedDay * SECONDS_IN_A_DAY);
        $unixMonth = date('M', $this->selectedDay * SECONDS_IN_A_DAY);

// end date stuff

        $this->setItemAtRowCol(0, 0, '<h4>' . $unixMonth . '</h4>');
        $this->setItemAtRowCol(0, 1, '<h4>' . $unixYear . '</h4>');

        $unixDayOfWeek = ($this->startDay + 3) % 7;

        for ($unixDay = $this->startDay; $unixDay < $this->startDay + 35; $unixDay++) {
            $unixSeconds = SECONDS_IN_A_DAY * $unixDay;
            $index = $unixDayOfWeek + $unixDay - $this->startDay;
            $row = $dayRow + 1 + intdiv($index, 7);
            $col = $index % 7;
            $year = date('Y', $unixSeconds);
            $month = date('M', $unixSeconds);
            $dayOfMonth = date('d', $unixSeconds);
//$table = new Table('table', 1, 1);
            $submitButton = new SubmitButton(SELECTED_DAY_POST_KEY, $dayOfMonth, $unixDay);

// html local style formatting code added here
            if ($unixDay === $unixToday) {
                $submitButton->addInside('class', 'today');
            } else if ($unixDay === $this->selectedDay) {
                $submitButton->addInside('class', 'selectedDay');
//echo 'selected found';
            } else {
                if ($unixMonth === $month) {
                    $submitButton->addInside('class', 'otherDay');
                } else {
                    $submitButton->addInside('class', 'otherMonth');
                }
            }

            $this->setItemAtRowCol($row, $col, $submitButton);
        }
    }

    public function setStartDay($newStart) {
        $this->startDay = $newStart;
        $this->build();
    }

    public function setSelectedDay($selectedDay) {
        $this->selectedDay = $selectedDay;
        $this->build();
        Session::setSelectedDay($this->selectedDay);
    }

    public function getSelectedDay() {
        return $this->selectedDay;
    }

    public function getStartDay() {
        return $this->startDay;
    }

}

class CalendarForm extends AutoPostingForm {

    public function __construct() {
        parent::__construct('calendarForm', true);
    }

    protected function build() {
        $this->form = new Form('calendarForm', 'Calendar', 'GET');
        $this->form->addInside('class', 'calendarForm');
        $this->form->addBetween(new calendarTable('calendarTable'));
    }

    protected function handleGET() {
        parent::handleGET();

        $calendarTable = $this->form->getByName('calendarTable');

        if (GetPost::getValueWithKey(CALENDAR_SCROLL_BY_POST_KEY)) {
            $startDay = intval(GetPost::getValueWithKey(CALENDAR_SCROLL_BY_POST_KEY));
            $calendarTable->setStartDay($startDay);
        }

        if (GetPost::getValueWithKey(SELECTED_DAY_POST_KEY)) {
            $selectedDay = intval(GetPost::getValueWithKey(SELECTED_DAY_POST_KEY));
            $calendarTable->setSelectedDay($selectedDay);
        }

        $calendarTable = $this->form->getByName('calendarTable');
//echo '<br>the start day' . $calendarTable->getStartDay();
//echo '<br>the selected day' . $calendarTable->getSelectedDay();
    }

}

class Today extends AutoPostingForm {

    public function __construct() {
        parent::__construct('todayForm');
    }

    protected function build() {
        $unixDay = Session::selectedDay() * SECONDS_IN_A_DAY;
        $dateString = date('d M Y', $unixDay);

        Database::connect();

        $SQL = 'SELECT * FROM workouts WHERE userid = :id AND unixday = :selectedDay';
        $preparedStatement = Database::prepare($SQL);
        $preparedStatement->execute(array(':id' => Session::userID(), ':selectedDay' => Session::selectedDay()));
        $recordsFound = $preparedStatement->fetchAll();
//print_r($recordsFound);

        $this->form = new Form('todayForm', $dateString);
        foreach ($recordsFound as $record) {

            $this->form->addBetween(new SubmitButton('submitButtonWorkout', $record['workout'], $record['id']));
        }
    }

}
