<?php 
require_once '../RooloClient.php';
require_once '../dataModels/Solution.php';
require_once '../Application.php';

error_reporting(E_ALL | E_STRICT);

$rooloClient = new RooloClient();
$curentSolution = new Solution();
//a flag for return selection
$firstTime = false;

/*
 * Decide which question to give a report on
 */
// if there's a problemUri in the request, serve that
if (isset($_REQUEST['problemUri'])){
	$questionUri = $_REQUEST['problemUri'];
}else{
	$questionUri = $questions[0]->get_uri();
	$firstTime = true;
}

/*
 * Decide which mode we're operating in: GROUP or SUPERGROUP
 */
$mode = $aggregateSolutionsMode;


/**
 * Find all the solutions 
 */
$authors = NULL;
$authorsStr = '';
if ($mode == 'GROUP'){
	$authors = Application::$groups;
}elseif ($mode == 'SUPERGROUP'){
	$authors = Application::$superGroups;
}
$authorsStr = implode(' OR ', $authors);

$query = 'type:Solution AND owneruri:'.$rooloClient->escapeSearchTerm($questionUri).' AND author:('.$rooloClient->escapeSearchTerm($authorsStr).')';
$solutions = $rooloClient->search($query, 'metadata', 'latest');

/*
 * Extract multiple choice count
 */
$mcCounter = array();
foreach (Application::$problemChoices as $curChoice){
	$mcCounter[$curChoice] = 0;
}

foreach ($solutions as $curSolution){
	$curMc = trim(mb_strtolower($curSolution->selectedchoice));
	
	if ($curMc != '' and $curMc != 'undefined'){
		$mcCounter[$curMc] += 1;
	}
}

/*
 * Extract category count
 */
$catCounter = array();
foreach (Application::$problemCategories as $curCat){
	$catCounter[$curCat] = 0;
}
foreach ($solutions as $curSolution){
	if (trim($curSolution->category) != ''){
		//$curCats = explode(",", trim(mb_strtolower($curSolution->category)));
		$curCats = explode(",", trim($curSolution->category));
		foreach ($curCats as $curCat){
			$curCat = trim($curCat);
			
			$catCounter[$curCat] += 1;	
		}
	}
}
if ($firstTime){
	$mcCounterJson = json_encode($mcCounter);
	$catCounterJson = json_encode($catCounter);
}else {
	$dataArray = array();
	$dataArray[0] = $mcCounter;
	$dataArray[1] = $catCounter;
	echo json_encode($dataArray);
	
}
?>