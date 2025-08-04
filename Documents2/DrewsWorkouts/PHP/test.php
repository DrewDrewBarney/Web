<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel = "stylesheet" type = "text/css" href = "../CSS/mystyle.css?version = 92228312" />

    </head>
    <body>
        <form action="" method="post">
            <input type="text" name="user1">
            <input type="password" name="password1">
            <input type="hidden" name="action1" value="login">
            <input type="submit" value="Login">
        </form>

        <br />

        <form action="" method="post">
            <input type="text" name="user2">
            <input type="password" name="password2">
            <input type="hidden" name="action2" value="register">
            <input type="submit" value="Register">
        </form>
    </body>
</html>

<?php

print_r($_POST);
