<?php

//include_once '../../Common/PHP/all.php';

class Database {

    protected static string $databasename = '';
    protected static string $username = '';
    protected static string $password = '';
    protected static ?PDO $pdo = null;

    public static function configure(string $databasename, string $username, string $password) {
        self::$databasename = $databasename;
        self::$username = $username;
        self::$password = $password;
    }

    public static function open(): void {

        if (self::isClosed()) {
            $hostname = 'localhost';
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$hostname;dbname=" . self::$databasename . ";charset=$charset;";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            self::$pdo = new PDO($dsn, self::$username, self::$password, $options);
        }
    }



    public static function isOpen(): bool {
        return !self::isClosed();
    }

    public static function close(): void {
        self::$pdo = null;
    }

    public static function isClosed(): bool {
        return self::$pdo === null;
    }

    public static function execute(string $queryString, array $parms = []): mixed {
        $query = self::$pdo->prepare($queryString);
        if ($query->execute($parms)) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
}
