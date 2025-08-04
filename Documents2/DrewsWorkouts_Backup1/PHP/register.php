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

        $registrationForm = new RegistrationForm();

        if ($registrationForm->registered()) {
            header('location: login.php');
            exit;
        } else {
            echo $registrationForm->toString();
        }
        ?>

    </body>
</html>
