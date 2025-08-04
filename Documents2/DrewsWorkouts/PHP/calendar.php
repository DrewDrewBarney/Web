<!DOCTYPE html>

<html>
    <head>
        <meta charset = "UTF-8">
        <title>Drew's Workouts</title>
        <link rel = "stylesheet" type = "text/css" href = "../CSS/mystyle.css?version = 222" />
    </head>
    <body>

        <?php
        session_start();
        require_once 'HTML.php';

        if (!Session::loggedIn())
            header('location: login.php');

        $cal = new CalendarForm();
        
        
        $today = new Today();

        if ($today->chosenWorkout() !== null)
            header('location: workoutBuilder.php');
         

        echo $cal->toString();
        echo $today->toString();

        scream(Session::selectedDay());
        //print_r($_GET);
        ?>


    </body>




</html>
