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
        session_destroy();
        require_once 'HTML.php';

        Database::connect();

        $form = new OptionsForm(['login', 'register']);

        switch ($form->getChoice()) {

            case 'login':
                header('location: login.php');
                exit;
                break;

            case 'register':
                header('location: register.php');
                exit;
                break;

            default:
                echo $form->toString();
                break;
        }
        
        //print_r($_GET);
        ?>

        <!--script src="mylib.js"></script-->

    </body>
</html>

