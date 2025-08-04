<?php

session_start();

/*
  print_r($_GET);
  echo '<br>';
  print_r($_POST);
  echo '<br>';
 * 
 */

include_once '../../Common/PHP/all.php';
include_once 'menu.php';

class Login {

    // some styles
    const TABLE_STYLE = 'width: 60ch;';
    const BUTTON_STYLE = 'margin: 2ch 0ch;';
    const LOGIN_BUTTON_STYLE = 'padding: 0ch 0ch;';
    const LOGOUT_BUTTON_STYLE = self::LOGIN_BUTTON_STYLE;
    const INPUT_STYLE = 'margin: 1ch 0ch;';
    const FIRST_ROW_COL_STYLE = 'width:20ch; font-size:1.5em; font-weight: bold; color: gray;';

    private Tag $html;
    private Tag $head;
    private Tag $body;
    private Tag $form;
    private Tag $article;
    private Tag $table;
    //
    // inputs
    private Tag $email;
    private Tag $password;
    private Tag $checkPassword;
    //
    // buttons
    private Tag $loginButton;
    private Tag $logoutButton;
    private Tag $askToRegisterButton;
    private Tag $registerButton;
    private Tag $resendConfirmationEmail;
    private Tag $okButton;

    public function __construct() {

        list($this->html, $this->head, $this->body) = makePage('Login / Register');

        $topBar = makeTopBar();

        $topBar->addChildren([
            makeMenu(MENU_CLUB_HOME, 'login.php'),
            makePageTitle('Login / Register')
        ]);

        $this->body->addChild($topBar);

        $this->form = $this->body->makeChild('form', '', ['method' => 'post', 'autocomplete' => 'off']);

        $this->article = $this->form->makeChild('article');

        $this->table = $this->article->makeChild('table', '', ['class' => 'loginTable', 'style' => self::TABLE_STYLE]);

        $this->body->addChild(makeFooter());

// inputs
        $this->email = Tag::make('input', '', ['name' => UserManagement::emailKey, 'id' => UserManagement::emailKey, 'type' => 'email', 'autocomplete' => 'off', 'style' => self::INPUT_STYLE]);
        $this->password = Tag::make('input', '', ['name' => 'password', 'id' => 'password', 'type' => 'password', 'autocomplete' => 'off', 'style' => self::INPUT_STYLE]);
        $this->checkPassword = Tag::make('input', '', ['name' => 'checkPassword', 'value' => '', 'id' => 'checkPassword', 'type' => 'password', 'autocomplete' => 'off', 'style' => self::INPUT_STYLE]);

// buttons
        $this->loginButton = Tag::make('button', '', ['type' => 'submit', 'name' => 'login', 'value' => 'login', 'style' => self::LOGIN_BUTTON_STYLE]);
        $this->loginButton->makeChild('img', '', ['src' => '../../Common/Images/Login.png']);
        $this->logoutButton = Tag::make('button', '', ['type' => 'submit', 'name' => 'logout', 'value' => 'logout', 'style' => self::LOGOUT_BUTTON_STYLE]);
        $this->logoutButton->makeChild('img', '', ['src' => '../../Common/Images/Logout.png']);
        $this->askToRegisterButton = Tag::make('button', 'register?', ['type' => 'submit', 'name' => 'register?', 'value' => 'register?', 'style' => self::BUTTON_STYLE]);
        $this->registerButton = Tag::make('button', 'register', ['type' => 'submit', 'name' => 'register', 'value' => 'register', 'style' => self::BUTTON_STYLE]);
        $this->resendConfirmationEmail = Tag::make('button', 'resend check email?', ['type' => 'submit', 'name' => 'resend', 'value' => 'resend', 'style' => self::BUTTON_STYLE]);
        $this->okButton = Tag::make('button', 'OK', ['type' => 'submit', 'name' => '', 'value' => '', 'style' => self::BUTTON_STYLE]);
    }

    function loginPage() {
        $this->body->setAttribute('class', 'loginPage');

        $tr = $this->table->makeChild('tr');
        $tr->makeChild('td');
        $tr->makeChild('td', 'Login', ['style' => self::FIRST_ROW_COL_STYLE]);

        $tr->makeChild('td', '', ['style' => 'text-align:right;'])->addChild($this->askToRegisterButton);

        $row = $this->table->makeChild('tr');
        $row->makeChild('td', 'email');
        $td = $row->makeChild('td');
        $td->addChild($this->email);

        $row = $this->table->makeChild('tr');
        $row->makeChild('td', 'password');
        $row->makeChild('td')->addChild($this->password);

        $this->table->makeChild('tr')->makeChild('td')->makeChild('td')->addChild($this->loginButton);
    }

    function logoutPage() {
        $this->body->setAttribute('class', 'logoutPage');

        $tr = $this->table->makeChild('tr');
        $tr->makeChild('td');
        $tr->makeChild('td', 'Logout ?', ['style' => self::FIRST_ROW_COL_STYLE]);
        $this->table->makeChild('tr')->makeChild('td')->makeChild('td')->addChild($this->logoutButton);
    }

    function pendingPage() {
        $this->table->makeChild('tr')->makeChild('td', 'account pending email confirmation', ['class' => 'error']);
        $tr = $this->table->makeChild('tr');
        $tr->makeChild('td')->addChild($this->resendConfirmationEmail);
    }

    function statusPage() {
        $this->table->makeChild('tr')->makeChild('td', UserManagement::message(), ['class' => 'error fadeIn', 'style' => 'text-align:left;']);
    }

    function registerPage() {
        $this->table->clearChildren(); // start from scratch

        $tr = $this->table->makeChild('tr');
        $tr->makeChild('td', 'Register', ['style' => self::FIRST_ROW_COL_STYLE]);

        $row = $this->table->makeChild('tr');
        $row->makeChild('td', 'email');
        $td = $row->makeChild('td');
        $td->addChild($this->email);

        $row = $this->table->makeChild('tr');
        $row->makeChild('td', 'password');
        $row->makeChild('td')->addChild($this->password);

        $row = $this->table->makeChild('tr');
        $row->makeChild('td', 'confirm password');
        $row->makeChild('td')->addChild($this->checkPassword);

        $this->table->makeChild('tr')->makeChild('td')->addChild($this->registerButton);
    }

    function confirmationEmailSentPage() {
        $this->table->makeChild('tr')->makeChild('td', 'confirmation email sent', ['class' => 'error']);
        $this->table->makeChild('tr')->makeChild('td')->addChild($this->okButton);
    }

    function respond() {
        if ($this->loginButton->pressed()) {
            UserManagement::login($this->email->value(), $this->password->value());
            if (UserManagement::loggedIn()) {
                header('Location: clubHome.php');
            } else if (UserManagement::pending()) {
                $this->loginPage();
                $this->pendingPage();
            } else {
                $this->loginPage();
                $this->statusPage();
            }
        } else if ($this->askToRegisterButton->pressed()) {
            $this->registerPage();
        } else if ($this->registerButton->pressed()) {
            UserManagement::register($this->email->value(), $this->password->value(), $this->checkPassword->value());
            $this->registerPage();
            $this->statusPage();
        } else if ($this->resendConfirmationEmail->pressed() && $this->email->value()) {
            UserManagement::sendConfirmationEmail($this->email->value());
            $this->confirmationEmailSentPage();
        } else if ($this->logoutButton->pressed()) {
            UserManagement::logout();
            header('Location: clubHome.php');
        } else { // either straight from menu so no button pressed or login button pressed
            if (UserManagement::loggedIn()) {
                $this->logoutPage();
            } else {
                $this->loginPage();
            }
        }
    }

    function render() {
        $this->html->echo();
    }
}

$login = new Login();
$login->respond();
$login->render();

