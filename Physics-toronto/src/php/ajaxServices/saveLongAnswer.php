<?php
session_start();

require_once '../RooloClient.php';
require_once '../Application.php';
require_once '../dataModels/LongAnswer.php';

$roolo = new RooloClient();

$username = $_SESSION['username'];

$longProblemUri = $_REQUEST['longProblemUri'];
$longProblemPath = $_REQUEST['longProblemPath'];
$conceptProblemUri = $_REQUEST['conceptProblemUri'];
$multipleChoice = $_REQUEST['multipleChoice'];
$categories = $_REQUEST['categories'];
$formulas = $_REQUEST['formulas'];
$rationale = $_REQUEST['rationale'];

//echo $longProblemUri." -- ";
//echo $longProblemPath." -- ";
//echo $conceptProblemUri." -- ";
//echo $multipleChoice." -- ";
//echo $categories." -- ";
//echo $formulas." -- ";
//echo $rationale." -- ";

$longAnswer = new LongAnswer();
$longAnswer->set_author($username);
$longAnswer->set_category($categories);
$longAnswer->set_formulas($formulas);
$longAnswer->set_conceptQuestionUri($conceptProblemUri);
$longAnswer->set_ownerUri($longProblemUri);
$longAnswer->set_questionPath($longProblemPath);

$roolo->addElo($longAnswer);

?>