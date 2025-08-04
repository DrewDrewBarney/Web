<?php

class RunningDatabase extends Database {

    public static function open(): void {
        $databasename = 'u952438166_running';
        $username = 'u952438166_drew';
        $password = 'Running4pleasure!';
        parent::configure($databasename, $username, $password);
        parent::open();
    }

    public static function execute(string $queryString, array $parms = []): mixed {
        if (parent::isClosed()) {
            self::open();
        }
        return parent::execute($queryString, $parms);
    }

}
