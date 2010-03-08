<?php

require_once '../RooloClient.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/Problem.php';
//require_once '../dataModels/Principle.php';

$rooloClient = new RooloClient();

$username = trim($_GET['username']);
$multipleChoiceMasterSolution = trim($_GET['masterSolution']);
$principleUri = trim($_GET['principleUri']);
$problemUri = trim($_GET['problemUri']);

$problemObject = new Problem();
$problemObject = $rooloClient->retrieveElo($problemUri);
$problemObject->author = $username;
$problemObject->mcmastersolution = $multipleChoiceMasterSolution;
$problemObject->principleuri = $principleUri;
$result = $rooloClient->updateElo($problemObject);

?>