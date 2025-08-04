<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once '../../Common/PHP/roots.php';
include_once   '../../Common/PHP/all.php'; 



class UserManagement {

    ///////////////////////////////////
    // need to be changed on deployment
    ///////////////////////////////////
    //
    //const userManagementPath = 'http://localhost/MyWebPages/PHP/UserManagement.php';
    //const loginPagePath = 'http://localhost/MyWebPages/Pages/login.php';
    //
    //////////////////////////////////////
    // keys for relevant session variables
    //////////////////////////////////////
    //
    const loggedInKey = 'loggedIn';
    //
    /////////////////////////////////////
    // keys for relevant database values
    //////////////////////////////////////
    //
    const emailKey = 'email';
    const confirmationTokenKey = 'confirmationtoken';
    //
    //////////////////
    // other constants
    //////////////////
    //
    const minimumPasswordLength = 8;
    //
    /////////////////////////////////////////////////////////////////////////////
    // messages
    /////////////////////////////////////////////////////////////////////////////
    //
    const noMessage = -3;
    const passwordsDiffer = -2;
    const unregistered = -1;
    const pending = 0;
    const registered = 1;
    const userAdded = 2;
    const alreadyRegistered = 3;
    const failedToRegister = 4;
    const noPassword = 5;
    const noCheckPassword = 6;
    const shortPassword = 7;
    const noEmail = 8;
    const incorrectPassword = 9;
    const loggedIn = 10;
    const messageStrings = [
        self::noMessage => '',
        self::passwordsDiffer => 'passwords differ',
        self::unregistered => 'not registered',
        self::pending => 'awaiting return of check email',
        self::registered => 'account registered',
        self::userAdded => 'user added',
        self::alreadyRegistered => 'already registered',
        self::failedToRegister => 'failed to register user',
        self::noPassword => 'no password entered',
        self::noCheckPassword => 'no check password entered',
        self::shortPassword => 'password is too short',
        self::noEmail => 'no email entered',
        self::incorrectPassword => 'incorrect password',
        self::loggedIn => 'logged in'
    ];

    //protected static $pdo = null;
    protected static $message = '';
    protected static $state = self::noMessage;
    protected static $result = 1;

    protected static function isRunningLocally() {
        return filter_input_array(INPUT_SERVER)['SERVER_NAME'] === 'localhost';
    }

    protected static function userManagementPHPfilePath(): string {
        return clientDocumentRoot() . 'Common/PHP/UserManagement.php';
        //return self::isRunningLocally() ? 'http://localhost/MyWebPages/Common/PHP/UserManagement.php' : 'https://drewshardlow.com/running_website/Common/PHP/UserManagement.php';
    }

    protected static function loginPagePath(): string {
        return clientDocumentRoot() . 'RunningSite/Pages/login.php';
        //return self::isRunningLocally() ? 'http://localhost/MyWebPages/RunningSite/Pages/login.php' : 'https://drewshardlow.com/running_website/RunningSite/Pages/login.php';
    }

    protected static function addUser(string $email, string $password): bool {
        $queryString = "INSERT INTO users (email, password, registrationStatus) VALUES(:email , :password, :registrationStatus);";
        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
        $parms = [$email, $encryptedPassword, self::pending];
        $result = Database::execute($queryString, $parms);
        if ($result) {
            self::$state = self::userAdded;
        } else {
            self::$state = self::failedToRegister;
        }
        return $result;
    }

    static function message(): string {
        return self::messageStrings[self::$state];
    }

    static function state(): int {
        return self::$state;
    }
    
    static function pending(): bool{
        return self::$state === self::pending;
    }

    static function login(string $email, string $password): int {
        self::$state = self::noMessage;

        if ($email === '') {
            self::$state = self::noEmail;
        } else if ($password === '') {
            self::$state = self::noPassword;
        } else {
            $userData = self::getUserData($email);
            if ($userData) {
                $encryptedPassword = $userData['password'];
                if (password_verify($password, $encryptedPassword)) {
                    self::$state = $userData['registrationStatus'];
                    if (self::$state === self::registered) {
                        self::$state = self::loggedIn;
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        $_SESSION[self::loggedInKey] = $email;
                    }
                } else {
                    self::$state = self::incorrectPassword;
                }
            } else {
                self::$state = self::unregistered;
            }
        }
        return self::$state;
    }

    static function logout(): void {
        $_SESSION[self::loggedInKey] = null;
        session_unset();
        session_destroy();
    }

    static function register(string $email, string $password, string $checkPassword) {
        self::$state = self::noMessage;

        if ($email === '') {
            self::$state = self::noEmail;
        } else if ($password === '') {
            self::$state = self::noPassword;
        } else if ($checkPassword === '') {
            self::$state = self::noCheckPassword;
        } else if ($password != $checkPassword) {
            self::$state = self::passwordsDiffer;
        } else if (strlen($password) <= self::minimumPasswordLength) {
            self::$state = self::shortPassword;
        } else {
            if (self::getUserData($email)) {
                self::$state = self::alreadyRegistered;
            } else {
                self::addUser($email, $password);
                if (self::getUserData($email)) {
                    self::$state = self::userAdded;
                    self::sendConfirmationEmail($email);
                } else {
                    self::$state = self::failedToRegister;
                }
            }
        }
    }

    static function generateConfirmationToken($email) {
        // Generate a random string (secure and cryptographically safe)
        $randomString = bin2hex(random_bytes(16)); // 16 bytes = 32 hex characters
        // Add a timestamp for time-sensitive tokens
        $timestamp = time(); // Current Unix timestamp
        // Combine email, random string, and timestamp
        $rawToken = $email . $randomString . $timestamp;

        // Hash the token using a secure algorithm (SHA-256)
        $hashedToken = hash('sha256', $rawToken);

        return $hashedToken;
    }

    static function buildConfirmationEmailContent(string $link): string {
        $title = "Drew's Resource for Runners";

        $html = Tag::make('html');
        $head = $html->makeChild('head');
        $body = $html->makeChild('body');

        $head->makeChild('meta', '', ['charset' => 'UTF-8']);
        $head->makeChild('meta', '', ['name' => 'description', 'content' => 'email to confirm registration on a website']);

        $body->makeChild('header', $title, Styles::header);
        $article = $body->makeChild('article', '', Styles::article);
        $article->makeChild('p', 'Please confirm your account email:');
        $article->makeChild('a', 'Confirm', ['href' => $link] + Styles::button);

        $footer = $body->makeChild('div', '', Styles::footer);
        $footer->makeChild('p', 'Dr Drew Shardlow', Styles::footerText);
        $footer->makeChild('p', 'Matha', Styles::footerText);
        $footer->makeChild('p', 'Nouvelle-Aquitaine');
        $footer->makeChild('p', 'France', Styles::footerText);
        $footer->makeChild('a', 'shardlow.a@gmail.com', ['href' => 'mailto:me@drewshardlow.com'] + Styles::footerText);

        $footer->makeChild('p',);

        $result = "<!DOCTYPE html>\r\n";
        $result .= $html->toString();

        return $result;
    }

    static function sendConfirmationEmail($email) {
        $data = self::getUserData($email);
        if ($data) {
            $token = self::generateConfirmationToken($email);
            $data['confirmationtoken'] = $token;
            self::setUserData($email, $data);
            $key = self::confirmationTokenKey;
            $path = self::userManagementPHPfilePath();

            $headers = "From: me@drewshardlow.com\r\n";
            $headers .= "Reply-To: me@drewshardlow.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $content = self::buildConfirmationEmailContent("$path?email=$email&$key=$token");
            mail($email, 'Registration', $content, $headers);
        }
    }

    static function receiveToken() {
        $email = filter_input(INPUT_GET, self::emailKey);
        $token = filter_input(INPUT_GET, self::confirmationTokenKey);

        if ($email && $token) {
            $data = self::getUserData($email);
            if ($data) {
                if ($token == $data[self::confirmationTokenKey]) {
                    $data['registrationStatus'] = self::registered;
                    self::setUserData($email, $data);
                    header('location: ' . self::loginPagePath() . '?' . self::emailKey . '=' . $email);
                }
            }
        }
    }

    protected static function getUserData(string $email): array {
        $queryString = "SELECT * FROM users WHERE email = :email";
        $result = RunningDatabase::execute($queryString, [$email]);
        return $result ? $result : [];
    }
    
    public static function count():int{
        $queryString = "SELECT COUNT(*) FROM users WHERE registrationStatus = :registrationStatus";
        $result = RunningDatabase::execute($queryString, [self::registered]);
        return $result ? $result['COUNT(*)'] : [];
    }

    protected static function setUserData(string $email, array $data) {
        $settings = '';
        $keyLast = array_key_last($data);
        foreach ($data as $key => $value) {
            $settings .= "$key = '$value'" . ($key === $keyLast ? '' : ', ');
        }
        $queryString = "UPDATE users SET $settings WHERE email = :email";
        RunningDatabase::execute($queryString, [$email]);
    }

    public static function email(): ?string {
        if (empty($_SESSION[self::loggedInKey])) {
            return null;
        } else {
            return $_SESSION[self::loggedInKey];
        }
    }

    public static function loggedIn(): bool {
        return self::email() !== null;
        /*
          if (empty($_SESSION[self::loggedInKey])) {
          return false;
          } else {
          return $_SESSION[self::loggedInKey] === $email;
          }
         * 
         */
    }
    
    public static function protect(): void{
        if (!self::loggedIn()){
            header('Location: home.php');
            exit();
        };
    }
}

///////////////////////////////////////////////////////////
/////////// code entry point after this page loads ////////
///////////////////////////////////////////////////////////


UserManagement::receiveToken();

