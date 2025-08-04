<?php

// tells us this is JSON
header('Content-Type: application/json');

include_once '../../Common/PHP/all.php';

/*
 * debug code

  echo json_encode($_POST);
  echo json_encode($_GET);
  echo json_encode(['drew'=>'shardlow', 'wife'=>['emma', 'clare']], JSON_PRETTY_PRINT);

  //exit();
 * 
 */



$email = Tag::make('input', '', ['name' => 'email', 'id' => 'email', 'type' => 'email']);
$password = Tag::make('password', '', ['name' => 'password', 'id' => 'password', 'type' => 'password']);

/*
 * debug code
 

$email->setValue('shardlow.a@gmail.com');
$password->setValue('123456');

/*
 * end debug code
 */


if ($email->value() && $password->value()) {

    //echo json_encode(['msg'=>'checking email/password...']);

    UserManagement::login($email->value(), $password->value());

    //echo json_encode(['msg'=>'loging in...']);


    if (UserManagement::loggedIn()) {
        
        $workoutForToday = UserData::get('MarathonPlan', 'workoutForToday');
        
        echo $workoutForToday;
        
        
    }
}


