<?php

include_once 'all.php';

class UserData extends RunningDatabase {
    
    const GLOBAL_DOMAIN = '';
    const PACE_VS_POWER = 'paceVsPower';

    public static function set(string $domain, string $key, string $value) {
        if (UserManagement::loggedIn()) {
            $email = UserManagement::email();
            $query = 'SELECT * FROM userData WHERE email = :email AND domain = :domain AND keyto = :key';
            $result = self::execute($query, [$email, $domain, $key]);
            if ($result) {
                //echo 'updating existing record';
                $query = 'UPDATE userData SET value = :value WHERE email = :email AND domain = :domain AND keyto = :key';
                self::execute($query, [$value, $email, $domain, $key]);
                //print_r($result);
            } else {
                //echo 'adding record';
                $query = 'INSERT INTO userData (email, domain, keyto, value) VALUES (:email, :domain, :key, :value);';
                $result = self::execute($query, [$email, $domain, $key, $value]);
                //print_r($result);
            }
        }
    }

    public static function get(string $domain, string $key): string {
        $result = '';
        if (UserManagement::loggedIn()) {
            $email = UserManagement::email();
            $query = 'SELECT * FROM userData WHERE email = :email AND domain = :domain AND keyto = :key';
            $result = self::execute($query, [$email, $domain, $key]);
            if ($result) {
                if (isset($result['value'])){
                    $result = $result['value'];
                } 
            }
        }
        return $result;
    }
}

/*
$test = array_combine(range(11,20,1), range(1,10,1));
    
foreach ($test as $key => $value) {
    UserData::set('shardlow.a@gmail.com', 'domm', $key, $value);
    $result = UserData::get('shardlow.a@gmail.com', 'dommy', $key);
    print_r($result);
}


$test = array_combine(range(101,110,1), range(201,210,1));
    
foreach ($test as $key => $value) {
    UserData::set('shardlow.a@gmail.com', 'domm', $key, $value);
    $result = UserData::get('shardlow.a@gmail.com', 'dommy', $key);
    print_r($result);
}
*/