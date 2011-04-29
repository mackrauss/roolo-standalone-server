<?php
require_once '../RooloClient.php';
require_once '../domLib/simple_html_dom.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/Solution.php';
require_once '../dataModels/AdvisedSolution.php';
require_once '../dataModels/SampleSolution.php';
require_once '../dataModels/RevisedSolution.php';
require_once '../dataModels/Concept.php';

	$username = trim($_REQUEST['username']);
	$runId = trim($_REQUEST['runId']);
	$selectedChoice = trim($_REQUEST['choice']);
	$ownerURI = trim($_REQUEST['ownerURI']);
	$reasonText = trim($_REQUEST['reason']);
	$problemPath = trim($_REQUEST['questionPath'])	;
	$category = $_REQUEST['category'];
	$options = $_REQUEST['options'];
	$categoryAndOptions = $_REQUEST['categoryAndOptions'];
	
	$solutionType = isset($_REQUEST['solutionType']) ? $_REQUEST['solutionType'] : 'Solution';  
	
	$rooloClient = new RooloClient();
	
	//create solution Object
	$solutionObject = new $solutionType();
	
	$solutionObject->solutiontype = 0; // 0 means multipleChoice
	$solutionObject->author = $username;
	$solutionObject->selectedchoice = $selectedChoice;
	$solutionObject->owneruri = $ownerURI;
	
	$reasonTag = $solutionObject->buildContentElement($reasonText, "rationale");
	$solutionObject->content = $reasonTag;
	
	$solutionObject->title = "Solution-Multiplechoice";
	$solutionObject->category = $categoryAndOptions;
	
	$solutionObject->runid = $runId;
	
	echo $results = $rooloClient->addElo($solutionObject);
?>