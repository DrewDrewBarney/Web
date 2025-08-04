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

        if (!Session::loggedIn()) {
            header('location: login.php');
        }
 
        $cal = new CalendarForm();
        echo $cal->toString();

        $today = new Today();
        echo $today->toString();
        ?>

        <script src='javascript/script.js ? version = 8>'></script>

    </body>




</html>
