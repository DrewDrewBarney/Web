<?php

class Styles {

    const topBar = '';
    const header = ['style' => 'margin: 0%; text-align: justify; padding: 1% 2%; font-family: Arial; font-size: 2em;'
        . 'background-color: lightgray; box-shadow: 0px 5px 5px gray;'];
    const article = ['style' => 'margin: 10% 15%; text-align: justify; padding: 1% 2%; font-family: Arial; font-size: 125%; color: darkslategray;'];
    
    const footer = ['style' => "
        height: 100vh;
        font-size: 100%;
        padding: 5px;
        color: white;
        background-image: linear-gradient(160deg, black, black, thistle, black, black);
        border-radius: 0px;
        "];
    
    const button = ['style' => 'font-size: 16px; margin-right: 20px; border: none; background: #084cdf; padding: '
        . '10px 20px;border-radius: 10px;color: #fff;cursor: pointer; text-decoration: none;'
    ];
    
    const footerText=['style'=> "
        color:lightyellow; text-decoration: none;
        "];
    
    const loggedIn = ['style'=>"
        position:fixed;
        top:0vh;
        right: 1vw;
        margin:0.5ch;
        padding:1ch;
        color:white;
        background-color:red;
        border-radius:10px;
       "];
    
    

}
