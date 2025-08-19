<?php

// diagonal.php
header('Content-Type: text/html; charset=UTF-8');


const a = <<< HD_SVG
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        
            
    <svg 
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 100 100"
        style="width: 100%; height: 100%;"
        preserveAspectRatio="xMidYMid meet"
        role="img" aria-label="Diagonal line"
    >
      <line
        x1="10" y1="80"
        x2="20" y2="90"
        stroke="black"
        stroke-width="5"
        stroke-linecap="round"
      />
       <line
        x1="20" y1="90"
        x2="90" y2="10"
        stroke="black"
        stroke-width="5"
        stroke-linecap="round"
      />       
    </svg>
  
    </body>
</html>
HD_SVG;

echo a;
