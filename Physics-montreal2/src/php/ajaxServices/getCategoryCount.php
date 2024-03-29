<?php 
require_once '../RooloClient.php';
require_once '../dataModels/Solution.php';
require_once '../Application.php';

error_reporting(E_ALL | E_STRICT);

$rooloClient = new RooloClient();
$curentSolution = new Solution();


$questionUri = '';
$mcChoice = '';
$mode = 'GROUP';

/*
 * get problemUri param
 */
if (isset($_REQUEST['problemUri'])){
	$questionUri = $_REQUEST['problemUri'];
}else{
	echo "ERROR: problemUri param not provided";
	die();
}

/*
 * get mcChoice param
 */
if (isset($_REQUEST['mcChoice'])){
	$mcChoice = $_REQUEST['mcChoice'];
}else{
	echo "ERROR: mcChoice param not provided";
	die();
}

/*
 * Get mode param (GROUP or SUPERGROUP)
 */
if (isset($_REQUEST['mode'])){
	$mode = $_REQUEST['mode'];
}

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
$query = 'type:Solution AND owneruri:'.$rooloClient->escapeSearchTerm($questionUri).' AND selectedchoice:'.$rooloClient->escapeSearchTerm($mcChoice).' AND author:('.$authorsStr.')';
$solutions = $rooloClient->search($query, 'metadata', 'latest');

/*
 * Extract category count
 */
$catCounter = array();
$rationales = array();
foreach (Application::$problemCategories as $curCat){
	$catCounter[$curCat] = 0;
}
foreach ($solutions as $curSolution){
	/*
	 * Get category count
	 */
	if (trim($curSolution->category) != ''){
		$curCats = explode(",", trim($curSolution->category));
		foreach ($curCats as $curCat){
			$curCat = trim($curCat);
			
			$catCounter[$curCat] += 1;	
		}
	}
	
	/*
	 * Get rationales
	 */
	$cleanedRationale = $curSolution->rationale;
	
	$rationales[] = array(
		'author' => $curSolution->get_author(),
		'text' => $cleanedRationale 
	);
}

$catCounterJson = json_encode($catCounter);
$rationalesJson = json_encode($rationales);

$finalJson = "{'rationales': $rationalesJson, 'catCounter': $catCounterJson}";
echo $finalJson;
?>