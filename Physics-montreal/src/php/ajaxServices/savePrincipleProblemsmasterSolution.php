<?php

require_once '../RooloClient.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/Problem.php';
//require_once '../dataModels/Principle.php';

$rooloClient = new RooloClient();

$username = trim($_REQUEST['username']);
$multipleChoiceMasterSolution = trim($_REQUEST['masterSolution']);
$principleUri = trim($_REQUEST['principleUri']);
$problemUri = trim($_REQUEST['problemUri']);

$problemObject = new Problem();
$problemObject = $rooloClient->retrieveElo($problemUri);
$problemObject->author = $username;
$problemObject->mcmastersolution = $multipleChoiceMasterSolution;
$problemObject->principleuri = $principleUri;
$result = $rooloClient->updateElo($problemObject);

?>