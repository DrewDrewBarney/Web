<?php

require_once 'constants.php';
require_once 'tools.php';



/*
 *      Session Wrapper Static Class
 */

class Server {

    public static function setValueWithKey($key, $value) {
        $_SERVER[$key] = $value;
    }

    public static function getValueWithKey($key, $defaults_to = null) {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        } else {
            return $defaults_to;
        }
    }

    public static function referrer() {
        return parse_url(self::getValueWithKey('HTTP_REFERER'), PHP_URL_PATH);
    }

    public static function self() {
        return parse_url(self::getValueWithKey('PHP_SELF'), PHP_URL_PATH);
    }

}

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

    /*
      public static function getRedirection(){
      return Session::getValueWithKey('pageRedirectionEvent') === true ? true : false;
      }

      public static function setRedirection(bool $truth){
      Session::setValueWithKey('pageRedirectionEvent', $truth);
      }
     * 
     */
}

class GETorPOST {

    public static function getValueWithKey($key, $default = null) {

        $result = $default;

        if (GETorPOST::verb() === 'GET') {
            if (isset($_GET[$key])) {
                $result = $_GET[$key];
            }
        } else if (GETorPOST::verb() === 'POST') {
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
            return null;
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

    protected $mID;  // the HTML tag ID for CSS
    protected $mTagName; // e.g.  div / input / table etc...
    protected $mHtmlName; // the HTML tag name (key for posted value in GET or POST superglobal.  We choose to generate this automatically and ensure it is unique
    protected $mPreviousValue;
    protected $mUpdatedFromGETorPOST;
    protected $mChanged;
    protected $mAttributes = [];

    public function __construct($ID, $tagName) {
        $this->mID = $ID;
        $this->mTagName = $tagName;
        $this->mHtmlName = $ID;
        $this->addAttribute('name', $this->mHtmlName); // name IS associated with POST data [name]=>data
        $this->addAttribute('id', $ID); // this ID will identify this object both in the PHP DOM and in the DOM for CSS styling
    }

    public function addAttribute($key, $value) {
        $this->mAttributes[$key] = $value;
    }

    public function removeAttribute($key) {
        unset($this->mAttributes[$key]);
    }

    public function clearAttributes() {
        $this->mAttributes = [];
    }

    public function &getByID($ID) {
        $result = null;
        if ($this->mID === $ID)
            $result = $this;
        return $result;
    }

    public function getID() {
        return $this->mID;
    }

    public function updated() {  // may still be the same, just means non-null value read from post and assigned
        return $this->mUpdatedFromGETorPOST;
    }

    public function changed() {
        return $this->mChanged;
    }

    public function getPreviousValue() {
        return $this->mPreviousValue;
    }

    public function setPreviousValue($param) {
        $this->mPreviousValue = $param;
    }

    public function getDomValue() { // return the value in the PHP DOM
        return isset($this->mAttributes['value']) ? $this->mAttributes['value'] : null;
    }

    public function setDomValue($value) {
        $this->mAttributes['value'] = $value;
    }

    public function GETorPOSTValue() {
        return GETorPOST::getValueWithKey($this->mHtmlName, null);
    }

    public function handleGETorPOST() {
        $this->mUpdatedFromGETorPOST = false;
        $this->mChanged = false;
        if ($this->GETorPOSTValue() !== null) {
            $this->mUpdatedFromGETorPOST = true;
            if ($this->getDomValue() !== $this->GETorPOSTValue()) {
                $this->setPreviousValue($this->getDomValue());
                $this->setDomValue($this->GETorPOSTValue());
                $this->mChanged = true;
            }
        }
    }

    public function handleNoGETorPOST() {
        // virtual
    }

    public function toString($tab = TAB8) {
        $insideTagStr = '';
        foreach ($this->mAttributes as $key => $value) {
            $insideTagStr .= ' ' . $key . '="' . $value . '"';
        }
        $result = $tab . "<" . $this->mTagName . $insideTagStr . ">\n";
        return $result;
    }

}

class Tags extends Tag {

    protected $mChildren = [];

    public function __construct($ID, $tagName) {
        parent::__construct($ID, $tagName);
    }

    public function setChild($textOrTag) {
        $this->mChildren = [$textOrTag];
    }

    public function addChild($textOrTag) {
        $this->mChildren[] = $textOrTag;
    }

    public function childCount($classNames = []) {
        if (count($classNames) === 0) {
            return count($this->mChildren);
        } else {
            $count = 0;
            foreach ($this->mChildren as $item) {
                if (array_search(get_class($item), $classNames) !== false) {
                    $count++;
                }
            }
            return $count;
        }
    }

    public function insertChild($index, $textOrTag) {
        array_splice($this->mChildren, $index, 0, [$textOrTag]);
    }

    public function deleteChildAtIndex($index) {
        array_splice($this->mChildren, $index, 1);
    }

    public function clearChildren() {
        $this->mChildren = [];
    }

// returns a reference to the stored item in mInBetweeners

    public function &getByID($ID) {
        $result = parent::getByID($ID);
        if ($result === null) {
            foreach ($this->mChildren as $child) {
                if (is_a($child, 'Tag')) {
                    $found = $child->getByID($ID);
                    if ($found !== null) {
                        $result = $found;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    public function &getChildAtIndex($index) {
        return $this->mChildren[$index];
    }

    public function handleGETorPOST() {
        parent::handleGETorPOST();
        foreach ($this->mChildren as $item) {
            if ($item instanceof Tag) {
                $item->handleGETorPOST();
            }
        }
    }

    public function handleNoGETorPOST() {
        parent::handleNoGETorPOST();
        foreach ($this->mChildren as $item) {
            if ($item instanceof Tag) {
                $item->handleNoGETorPOST();
            }
        }
    }

    public function toString($tab = TAB8) {
        $result = parent::toString($tab);
        foreach ($this->mChildren as $item) {
            if ($item instanceof Tag) {
                $result .= $item->toString($tab . TAB4);
            } else if (is_string($item)) {
                $result .= $tab . TAB4 . $item . "\n";
            }
        }
        $result .= $tab . '</' . $this->mTagName . ">\n";
        return $result;
    }

}

class Script extends Tags {

    public function __construct() {
        parent::__construct('script', 'script');
    }

}

class Heading extends Tags {

    public function __construct($ID, $tagName, $heading) {
        parent::__construct($ID, $tagName);
        $this->addChild($heading);
    }

}

class Option extends Tags {

    public function __construct($ID, $value) {
        parent::__construct($ID, 'option');
        $this->addAttribute('value', $value);
        $this->addChild($value);
    }

}

class Select extends Tags {

    protected $options;

    public function __construct($ID, $options, $defaultOption = '') {
        parent::__construct($ID, 'select');
        $this->options = $options;
        $this->addAttribute('value', $defaultOption);
        $this->addAttribute('onchange', 'requestPost()');
        $this->build();
    }

    public function handleGETorPOST() {
        parent::handleGETorPOST();
        $this->build();
    }

    protected function build() {
        $this->clearChildren();
        foreach ($this->options as $option) {
            $optionTag = new Option('option', $option);
            if ($option === $this->getDomValue()) {
                $optionTag->addAttribute('selected', 'selected');
            }
            $this->addChild($optionTag);
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

    public function __construct($ID, $defaultValue = '') {
        parent::__construct($ID, 'input');
        $this->mErrorLabel = new Label('label', $this->getID(), '');
        $this->addChild($this->mErrorLabel);
        $this->addAttribute('type', 'text');
        $this->addAttribute('value', $defaultValue);
        $this->addAttribute('onfocusout', 'requestPost()');
    }

    public function setType($type) {
        $this->mInputType = $type;
        $this->refreshErrorState();
    }

    protected function refreshErrorState() {
        $value = $this->getDomValue();
        $validValue = $this->validateValue($value);
        if ($validValue === null) {
            $this->addAttribute('value', $value);
            $this->setError('invalid format');
        } else {
            $this->addAttribute('value', $validValue);  // add the raw invalid value to the control
            $this->clearError();
        }
    }

    public function handleGETorPOST() {
        parent::handleGETorPOST();
        $this->refreshErrorState();
    }

    public function setError($msg) {
        $this->mErrorLabel->setChild('?');
        $this->mErrorLabel->addAttribute('class', 'error');
        $labelMsg = new Label('label', $this->mErrorLabel->getID(), $msg);
        $labelMsg->addAttribute('class', 'error_message');
        $this->mErrorLabel->addChild($labelMsg);
    }

    protected function clearError() {
        $this->mErrorLabel->clearChildren();
        $this->mErrorLabel->clearAttributes();
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

class HiddenInput extends Tag {

    public function __construct($ID) {
        parent::__construct($ID, 'input');
        $this->addAttribute('type', 'hidden');
    }

}

class HiddenInputFormID extends HiddenInput {

    public function __construct($autoPostingFormContainedFormID) {
        parent::__construct($autoPostingFormContainedFormID);
    }

    public function handleGETorPOST() {
        parent::handleGETorPOST();
        $this->setDomValue(uniqid());
    }

}

class HiddenReferrer extends HiddenInput {

    public function handleGETorPOST() {
        parent::handleGETorPOST();
        $this->addAttribute('value', Server::referrer());
    }

}

class Form extends Tags {

    protected $mTitle = '';
    protected $mFormID;
    protected $mScript;

    public function __construct($ID, $title = '', $verb = 'POST', $action = '', $postOnChange = false) {
        parent::__construct($ID, 'form');
        $this->mTitle = $title;
        $this->addAttribute('method', $verb);
        $this->addChild($this->mFormID = new HiddenInputFormID($ID));
        $this->addChild($this->mScript = new Script());
        if ($postOnChange) {
            $this->mScript->setChild(
                    "
            var gNeedsPost = false;

            window.setInterval(postOnRequest, 100);

            function postOnRequest(){
                if (gNeedsPost){
                    gNeedsPost = false;
                    document.getElementById('$this->mID').submit();
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

    public function ID() {
        return $this->mFormID->getDomValue();
    }

    public function toString($tab = TAB8): string {
        $result = "\n" . $tab . '<h2>' . $this->mTitle . "</h2>\n";
        $result .= parent::toString($tab = TAB8);
        return $result;
    }

}

class Label extends Tags {

    public function __construct($ID, $boundInputID, $caption) {
        parent::__construct($ID, 'label');
        $this->addAttribute('for', $boundInputID);
        $this->addChild($caption);
    }

}

class Button extends Tags {

    public function __construct($ID, $caption, $value = 'default', $action = '') {
        parent::__construct($ID, 'button');
        $this->addAttribute('type', 'submit');
        $this->addAttribute('value', $value);
        $this->addAttribute('formaction', $action);
        $this->addChild($caption);
    }

}

class SubmitButton extends Tags {

    public function __construct($ID, $caption, $value = 'default') {
        parent::__construct($ID, 'button');
        $this->addAttribute('value', $value);
        $this->addChild($caption);
    }

    public function pressed() {
        return $this->mUpdatedFromGETorPOST;
    }

    public function handleGETorPOST() {
        parent::handleGETorPOST();
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

    protected $form;
    protected $sessionKey;
    protected $persistent = false;
    protected $oldVerb = '';
    protected $script;

    public function __construct($sessionKey, $persistent = false) {

        $this->sessionKey = $sessionKey;
        $this->persistent = $persistent;
        $this->script = new Script();

        if ($persistent)
            $this->loadFormFromSession();

        if ($this->form === null)
            $this->build();


        if (GETorPOST::verb() == 'POST') {
            $this->handlePOST();
        } else if (GETorPOST::verb() == 'GET') {
            $this->handleGET();
        } else {
            $this->handleNoGETorPOST();
        }

        scream('VERB ' . GETorPOST::verb());
    }

    public function __destruct() {
        $this->storeFormInSession();
    }

    protected function handleGET() {
        $this->form->handleGETorPOST(); // request DOM to update based on the GET or POST
    }

    protected function handlePOST() {
        $this->form->handleGETorPOST();
    }

    protected function handleNoGETorPOST() {
        $this->form->handleNoGETorPOST();
    }

    protected function build() {
        scream('===> new build');
        $this->form = new Form('autopostingFormDefaultForm', 'default autoposting form form');
    }

    protected function storeFormInSession() {
        $key = $this->form->ID();
        Session::setValueWithKey($key, serialize($this->form));
    }

    protected function loadFormFromSession() {
        $key = GETorPOST::getValueWithKey($this->sessionKey);
        $form = Session::getValueWithKey($key);
        $this->form = $form === null ? null : unserialize($form);
    }

    public function &getForm() {
        return $this->form;
    }

    public function setForm(&$form) {
        $this->form = $form;
    }

    public function toString($tab = TAB8) {
        $result = "\n<!-- START OF GENERATED HTML CODE PRODUCED BY DREW'S PHP FRAMEWORK -->\n";
        $result .= $this->form->toString($tab = TAB8);
        $result .= $this->script->toString();
        $result .= "\n<!-- END OF GENERATED HTML CODE PRODUCED BY DREW'S PHP FRAMEWORK -->\n\n";
        return $result;
    }

}

class LoginForm extends AutoPostingForm {

    protected $PDO = null;
    protected $loggedIn = false;

    protected function handlePOST() {
        parent::handlePOST();

// get handles to the form objects
        $table = & $this->form->getByID('table');
        $usernameInput = & $this->form->getByID('usernameInput');
        $passwordInput = & $this->form->getByID('passwordInput');

// deal with the posted data
        $usernameTry = $usernameInput->getDomValue();
        $passwordTry = $passwordInput->getDomValue();

        $ballcarry = true;

        if ($ballcarry) {
            if ($usernameTry === '') {
                $usernameInput->setError('enter username');
                $table->setItemAtRowCol(2, 1, 'error(s) on form');
                $ballcarry = false;
            }

            if ($passwordTry === '') {
                $passwordInput->setError('enter password');
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
                    $table->setItemAtRowCol(1, 2, 'incorrect password');
                    $passwordInput->setError('incorrect password');
                    $ballcarry = false;
                }
            } else {
                $table->setItemAtRowCol(0, 2, 'username not recognised.');
                $usernameInput->setError('username not recognised');
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
        $table = new Table('table', 3, 3, true);
        $form->addChild($table);

        $inputUsername = new InputText('usernameInput');
        $table->setItemAtRowCol(0, 1, $inputUsername);
        //$table->setItemAtRowCol(0, 0, $inputUsername->createLabel('userNameLabel', 'User Name:'));
        $table->setItemAtRowCol(0, 0, new Label('label', $inputUsername->getID(), 'User Name'));

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
    protected $optionButtons = [];

    public function __construct($options) {
        $this->options = $options;
        parent::__construct('optionsForm', true);
    }

    protected function handleGET() {
        parent::handleGET();
    }

    protected function build() {
        $this->form = new Form('options_form', 'Options', 'GET');
        $numberOfOptions = count($this->options);
        $table = new Table('options_table', $numberOfOptions, 1);
        $this->form->addAttribute('class', 'optionForm');
        $this->form->addChild($table);

        $row = 0;
        foreach ($this->options as $option) {
            $button = new SubmitButton($option, $option, $option);
            $this->optionButtons[] = $button;
            $table->setItemAtRowCol($row, 0, $button);
            $row++;
        }
    }

    public function getChoice() {
        $result = null;
        $table = $this->form->getByID('options_table');
        for ($row = 0; $row < $table->rows(); $row++) {
            $button = $table->getItemAtRowCol($row, 0);
            if ($button->pressed()) {
                $result = $button->getDomValue();
            }
        }
        return $result;
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
        $form->addChild($table);

        $inputUsername = new InputText('usernameInput');
        $table->setItemAtRowCol(0, 1, $inputUsername);
        $table->setItemAtRowCol(0, 0, new Label('label', $inputUsername->getID(), 'User Name'));

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
        $table = & $this->form->getByID('table');
        $usernameInput = & $this->form->getByID('usernameInput');
        $passwordInput = & $this->form->getByID('passwordInput');

// get posted values
        $username = $usernameInput->getDomValue();
        $password = $passwordInput->getDomValue();

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

class Table extends Tags {

    protected $mRows;
    protected $mCols;

    public function __construct($ID, $rows, $cols, $makeUnique = false) {
        $this->mRows = $rows;
        $this->mCols = $cols;
        parent::__construct($ID, 'table', $makeUnique);
        for ($row = 0; $row < $rows; $row++) {
            $rowIdentifier = "row_" . $row;
            $newRow = new Tags($rowIdentifier, 'tr');
            for ($col = 0; $col < $cols; $col++) {
                $itemIdentifier = 'col_' . $col;
                $newData = new Tags($itemIdentifier, 'td');
                $newRow->addChild($newData);
            }
            $this->addChild($newRow);
        }
    }

    public function rows() {
        return $this->mRows;
    }

    public function cols() {
        return $this->mCols;
    }

    public function setItemAtRowCol($row, $col, $item) {
        $tableRow = & $this->getChildAtIndex($row);
        $tableData = & $tableRow->getChildAtIndex($col);
        $tableData->setChild($item);
    }

    public function &getItemAtRowCol($row, $col) {
        $tableRow = & $this->getChildAtIndex($row);
        $tableData = & $tableRow->getChildAtIndex($col);
        return $tableData->getChildAtIndex(0);
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

        $this->addAttribute('class', 'calendarTable');

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
                $submitButton->addAttribute('class', 'today');
            } else if ($unixDay === $this->selectedDay) {
                $submitButton->addAttribute('class', 'selectedDay');
//echo 'selected found';
            } else {
                if ($unixMonth === $month) {
                    $submitButton->addAttribute('class', 'otherDay');
                } else {
                    $submitButton->addAttribute('class', 'otherMonth');
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
        $this->form->addAttribute('class', 'calendarForm');
        $this->form->addChild(new calendarTable('calendarTable'));
        $this->form->addChild(new Button('cheeky', 'cheeky', 'stuff', 'workoutBuilder.php'));
    }

    protected function handleGET() {
        parent::handleGET();

        $calendarTable = $this->form->getByID('calendarTable');

        if (GETorPOST::getValueWithKey(CALENDAR_SCROLL_BY_POST_KEY)) {
            $startDay = intval(GETorPOST::getValueWithKey(CALENDAR_SCROLL_BY_POST_KEY));
            $calendarTable->setStartDay($startDay);
        }

        if (GETorPOST::getValueWithKey(SELECTED_DAY_POST_KEY)) {
            $selectedDay = intval(GETorPOST::getValueWithKey(SELECTED_DAY_POST_KEY));
            $calendarTable->setSelectedDay($selectedDay);
        }

        $calendarTable = $this->form->getByID('calendarTable');
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
        $preparedStatement->execute([':id' => Session::userID(), ':selectedDay' => Session::selectedDay()]);
        $recordsFound = $preparedStatement->fetchAll();
//print_r($recordsFound);

        $this->form = new Form('todayForm', $dateString, 'GET');
        
        foreach ($recordsFound as $record) {

            $this->form->addChild(new Button('SelectedWorkoutID', $record['workout'], $record['id'], 'WorkoutBuilder.php'));
        }

        //$this->form->addChild(new Button('chosen', 'choose', '1', 'WorkoutBuilder.php'));
        
    }

    public function chosenWorkout() {
        $form = $this->getForm();
        foreach ($form as $item) {
            echo $item;
        }
        return null;
    }

}
