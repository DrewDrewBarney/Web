<?php

/*
 * 
 * 
 * /Library/WebServer/Documents
 * 
 * /home/u952438166/domains/drewshardlow.com/public_html/ClubPages/
 * 
 * 
 */
 
/*
// server side document root
function serverDocumentRoot(){
    $docRoot = $_SERVER['DOCUMENT_ROOT'] . '/';
    return $docRoot === '/Library/WebServer/Documents/' ? $docRoot . 'MyWebPages/' : $docRoot;
}

//echo 'server document root is ' . serverDocumentRoot();

function clientDocumentRoot(){
    $docRoot = $_SERVER['SERVER_NAME'] . '/';
    return $docRoot === 'localhost/' ? 'http://' . $docRoot . 'MyWebPages/' : 'https://' . $docRoot;
}
 * *
 */

include_once '../../Common/PHP/roots.php';

$CommonPHPpath =  serverDocumentRoot() . 'Common/PHP/';

include_once $CommonPHPpath . 'menu.php';
include_once $CommonPHPpath . 'Constants.php';
include_once $CommonPHPpath . 'SimpleTable.php';
include_once $CommonPHPpath . 'Table.php';

include_once $CommonPHPpath . 'dom.php';
include_once $CommonPHPpath . 'domCon.php';
include_once $CommonPHPpath . 'Tools.php';

include_once $CommonPHPpath . 'statistics.php';
include_once $CommonPHPpath . 'GoogleChart.php';
include_once $CommonPHPpath . 'SimpleTable.php';
include_once $CommonPHPpath . 'CheckValue.php';
include_once $CommonPHPpath . 'Field.php';
include_once $CommonPHPpath . 'Database.php';
include_once $CommonPHPpath . 'RunningDatabase.php';
include_once $CommonPHPpath . 'UserManagement.php';
include_once $CommonPHPpath . 'UserData.php';
include_once $CommonPHPpath . 'inlineStylesForEmails.php';




/*
echo $_SERVER['DOCUMENT_ROOT'];
echo $_SERVER['SERVER_NAME'];
 * 
 */