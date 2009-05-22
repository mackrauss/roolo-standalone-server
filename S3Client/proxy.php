<?php
$serviceUrl = $_REQUEST['serviceUrl'];
$action = $_REQUEST['action'];
$paramName = $_REQUEST['paramName'];
$paramValue = $_REQUEST['paramValue'];

$file = fopen ($serviceUrl.$action."?".$paramName."=".$paramValue, "r");

while (!feof ($file)) {
    $line = fgets ($file, 1024);
    echo $line;
}
fclose($file);
?>