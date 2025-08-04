<?php

session_start();

include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../ClubPHP/clubAll.php';
include_once 'menu.php';

list($html, $head, $body) = makePage('Login / Register');

$topBar = makeTopBar();

$topBar->addChildren([
    makePageTitle("Athletic Club Angerien"),
    makeMenu(MENU_CLUB_HOME, 'login.php'),
    makePageTitle('Login / Register')
]);

$body->addChild($topBar);

$trainingArticle = $body->makeChild('article');

if (array_key_exists('token', $_GET)) {
    $trainingArticle->makeChild('p', 'Your account is confirmed!', ['class' => 'error']);
}


// some styles
$tableStyle = 'width: 60ch;';
$buttonStyle = 'margin: 2ch 0ch;';
$inputStyle = 'margin: 1ch 0ch;';
$firstRowColStyle = 'width:20ch; font-size:1.5em; font-weight: bold; color: gray;';

// inputs
$email = Tag::make('input', '', ['name' => UserManagement::emailKey, 'id' => UserManagement::emailKey, 'type' => 'email', 'autocomplete' => 'off', 'style' => $inputStyle]);
$password = Tag::make('input', '', ['name' => 'password', 'id' => 'password', 'type' => 'password', 'autocomplete' => 'off', 'style' => $inputStyle]);
$checkPassword = Tag::make('input', '', ['name' => 'checkPassword', 'value' => '', 'id' => 'checkPassword', 'type' => 'password', 'autocomplete' => 'off', 'style' => $inputStyle]);

// buttons
$loginButton = Tag::make('button', 'log in', ['type' => 'submit', 'name' => 'login', 'value' => 'login', 'style' => $buttonStyle]);
$logOutButton = Tag::make('button', 'log out?', ['type' => 'submit', 'name' => 'logout', 'value' => 'logout', 'style' => $buttonStyle]);
$askToRegisterButton = Tag::make('button', 'register?', ['type' => 'submit', 'name' => 'register?', 'value' => 'register?', 'style' => $buttonStyle]);
$registerButton = Tag::make('button', 'register', ['type' => 'submit', 'name' => 'register', 'value' => 'register', 'style' => $buttonStyle]);
$returnToLoginButton = Tag::make('button', 'return to login', ['type' => 'submit', 'name' => 'returnToLogin', 'value' => 'returnToLogin', 'style' => $buttonStyle]);
$resendConfirmationEmail = Tag::make('button', 'resend check email?', ['type' => 'submit', 'name' => 'resend', 'value' => 'resend', 'style' => $buttonStyle]);

$form = $trainingArticle->makeChild('form', '', ['method' => 'post', 'class' => 'loginForm', 'autocomplete' => 'off']);

$state = Tools::returnSelectedButtonValue([$loginButton, $askToRegisterButton, $registerButton, $returnToLoginButton, $resendConfirmationEmail, $logOutButton]);

// LOGIN OR REGISTER DEPENDING UPON THE BUTTON PRESSED

switch (true) {

    case empty($state) && UserManagement::loggedIn():

        $table = $form->makeChild('table', '', ['class' => 'loginTable', 'style' => $tableStyle]);
        $table->makeChild('tr')->makeChild('td', 'Log Out?', ['style' => $firstRowColStyle]);
        $table->makeChild('tr')->makeChild('td')->addChild($logOutButton);
        break;

    case $state == 'resend':
        if ($email->value()) {
            UserManagement::sendConfirmationEmail($email->value());
            $trainingArticle->makeChild('p', 'sent', ['class' => 'error']);
        }
    case $state == 'logout':
        UserManagement::logOut();
        $trainingArticle->makeChild('p', 'logged out', ['class' => 'error']);

    case (empty($state) || $state == 'returnToLogin' || $state == 'login'):

        //$email->setValue('my@email');
        //$password->setValue('?');

        $table = $form->makeChild('table', '', ['class' => 'loginTable', 'style' => $tableStyle]);

        $tr = $table->makeChild('tr');
        $tr->makeChild('td', 'Login', ['style' => $firstRowColStyle]);
        $tr->makeChild('td', '', ['style' => 'text-align:right;'])->addChild($askToRegisterButton);

        $row = $table->makeChild('tr');
        $row->makeChild('td', 'email');
        $td = $row->makeChild('td');
        $td->addChild($email);
      
        $row = $table->makeChild('tr');
        $row->makeChild('td', 'password');
        $row->makeChild('td')->addChild($password);

        $table->makeChild('tr')->makeChild('td')->addChild($loginButton);

        if ($state === 'login') {
            UserManagement::login($email->value(), $password->value());
            if (UserManagement::loggedIn()){
                header('Location: clubHome.php');
            }
        }

        $table->makeChild('tr')->makeChild('td', UserManagement::message(), ['class' => 'error fadeIn', 'style' => 'text-align:left;']);

        if (UserManagement::state() === UserManagement::pending) {
            $table->makeChild('tr')->makeChild('td')->addChild($resendConfirmationEmail);
        }

        break;

    case($state == 'register?' || $state == 'register'):

        $table = $form->makeChild('table', '', ['class' => 'loginTable', 'style' => $tableStyle]);
        $tr = $table->makeChild('tr');
        $tr->makeChild('td', 'Register', ['style' => $firstRowColStyle]);
        $tr->makeChild('td', '', ['style' => 'text-align: right;'])->addChild($returnToLoginButton);

        $row = $table->makeChild('tr');
        $row->makeChild('td', 'email', ['style' => 'width:20ch;']);
        $row->makeChild('td')->addChild($email);

        $row = $table->makeChild('tr');
        $row->makeChild('td', 'password');
        $row->makeChild('td')->addChild($password);

        $row = $table->makeChild('tr');
        $row->makeChild('td', 'check password');
        $row->makeChild('td')->addChild($checkPassword);

        $table->makeChild('tr')->makeChild('td')->addChild($registerButton);

        if ($state === 'register') {
            UserManagement::register($email->value(), $password->value(), $checkPassword->value());
        }

        $table->makeChild('tr')->makeChild('td', UserManagement::message(), ['class' => 'error fadeIn', 'style' => 'text-align:left;']);
        break;

    default:
        $trainingArticle->makeChild('p', 'Hmmm. Something erred. $state == ' . $state, ['class' => 'error']);
        break;
}

$body->addChild(makeFooter());

$html->echo();

