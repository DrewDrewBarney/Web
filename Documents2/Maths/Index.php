


<html>
    <head>

        <link rel="stylesheet" href="mathcss.css?id=<?php echo rand(); ?>">

    </head>

    <body>
        <div style ="height: 25px;width: 25px; display: inline-block; position: relative; bottom: -0.5ch; left: 0;" class="forwardSlash">
            one
        </div>
        <div style ="height: 50px;width: 25px; display: inline-block; position: relative; bottom: 0; left: 0;" class="forwardSlash">
            one
        </div>


    </body>


</html>









<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'Parser.php';

echo 'hello world!';

$tokeniser = new Tokeniser('raw ru  [3,3] + 3*([1,2.2])');

while (!$tokeniser->EOT()){
    echo $tokeniser->getToken();
}
