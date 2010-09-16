<?php
include_once '../RooloClient.php';

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
`rm -rf ../../../problems/*`;

/*
 * Empty /graphml
 */
`rm -rf ../../../graphml/*`;

?>