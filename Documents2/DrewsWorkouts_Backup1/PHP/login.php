<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Drew's Workouts</title>

        <link rel = "stylesheet" type = "text/css" href = "../CSS/mystyle.css?version = 222" />
    </head>
    <body>

        <?php
        session_start();
        require_once 'HTML.php';

        $loginForm = new LoginForm('form');

        if ($loginForm->loggedIn()) {
            header('location: calendar.php');
        } else {
            echo $loginForm->toString();
        }
        

        ?>

        <!--script src="script.js"></script-->


    </body>
</html>
