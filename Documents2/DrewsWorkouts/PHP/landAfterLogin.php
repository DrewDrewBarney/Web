<!DOCTYPE html>

<html>
    <head>
        <title>Drew's Workouts</title>
    </head>
    <body>
        <?php
            session_start();
            if (isset($_SESSION['loggedIn'])){
                echo '<h1>Logged in!</h1>';
            }
        ?>
    </body>
</html>


