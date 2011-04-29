<?php
header("Cache-Control: no-cache, must-revalidate");

include_once '../RooloClient.php';
include_once '../CommonFunctions.php';

/*
 * Setup variables
 */
$roolo = new RooloClient();
 
/*
 * Empty Roolo
 */
$roolo->deleteAll(); 


/*
 * Empty /problems
 */
//`rm -rf ../../../problems/*`;
deleteDirContents('../../../problems');

/*
 * Empty /graphml
 */
//`rm -rf ../../../graphml/*`;
deleteDirContents('../../../graphml');
?>