<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            include_once 'Scene.php';
            $play = new Play();
            $scene = new Scene();
            $play->addScene($scene);
            $play->addScene($scene);
            
            $play->show();
        ?>
    </body>
</html>
