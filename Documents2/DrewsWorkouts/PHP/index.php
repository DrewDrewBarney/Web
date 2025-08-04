<?php 
        require_once 'HTML.php';
        //if (GetPost::verb() === null) session_destroy();

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
                ?>
<html>
    <head>
        <meta charset = "UTF-8">
        <title>Drew's Workouts</title>
        <link rel = "stylesheet" type = "text/css" href = "../CSS/mystyle.css?version = 222" />
    </head>
    <body>        
                <?php
                
                
                echo $form->toString();
                break;
        }
        
        print_r($_GET);
        ?>

        <!--script src="mylib.js"></script-->

    </body>
</html>

