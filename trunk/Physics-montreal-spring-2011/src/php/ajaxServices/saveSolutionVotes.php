<?php
require_once '../RooloClient.php';
require_once '../domLib/simple_html_dom.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/Solution.php';
require_once '../dataModels/VoteUp.php';
require_once '../dataModels/VoteDown.php';
require_once '../dataModels/AdvisedSolution.php';
require_once '../dataModels/SampleSolution.php';
require_once '../dataModels/RevisedSolution.php';
require_once '../dataModels/Concept.php';

$votes = json_decode($_REQUEST['votes']);
$username = trim($_REQUEST['username']);
$runId = trim($_REQUEST['runId']);

$rooloClient = new RooloClient();

foreach ($votes as $curSolutionUri => $voteValue){
	$vote = $voteValue == -1 ? new VoteDown() : new VoteUp();
	
	$vote->owneruri = $curSolutionUri;
	$vote->author = $username;
	$vote->runid = $runId;
	
	$rooloClient->addElo($vote);
}
	
	
?>