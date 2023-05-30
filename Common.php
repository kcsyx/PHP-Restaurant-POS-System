<?php
require_once('DB.class.php'); // controls all the connection to Database
require_once('ControllerFunction.php');
require_once('ControllerDisplay.php');

// initialising DB conection
$DB = new DB();
$DB->host = "localhost";
$DB->user = "root";
$DB->password = "root";
$DB->db = "uxg2520su23software_systemdesigners";
// modify this and add _[yourTeamName]. When you create the database, make sure they are the same database name
// Example: uxg2520su23software_AlwynTeamName

// to print out Array in a nice HTML format for easy reading
function printArray($array)
{
    echo "<pre>";
    echo "<p>=== FOR DEBUGGING PURPOSES ====</p>";
    print_r($array);
    echo "<p>===============================</p>";
    echo "</pre>";
}

// to print out the top green header and show what page you are on
function displayPageHeader($pageName)
{
    $pageHeader = sprintf("<h1><font color='#00008B'>%s</font></h1>", $pageName);
    echo $pageHeader;
}
?>