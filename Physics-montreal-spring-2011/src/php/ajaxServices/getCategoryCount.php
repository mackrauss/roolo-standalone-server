<?php 
require_once '../RooloClient.php';
require_once '../dataModels/Solution.php';
require_once '../Application.php';

error_reporting(E_ALL | E_STRICT);


$rooloClient = new RooloClient();
$curentSolution = new Solution();
$solutionObject = new Solution();


$questionUri = '';
$mcChoice = '';
$mode = 'group';

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

/*
 * get runId param
 */
if (isset($_REQUEST['runId'])){
	$runId = $_REQUEST['runId'];
}else{
	echo "ERROR: runId param not provided";
	die();
}

///**
// * Find all the solutions 
// */
//$authors = NULL;
//$authorsStr = '';
//if ($mode == 'GROUP'){
//	$authors = Application::$groups;
//}elseif ($mode == 'SUPERGROUP'){
//	$authors = Application::$superGroups;
//}
//$authorsStr = implode(' OR ', $authors);

//$query = 'type:Solution AND owneruri:'.$rooloClient->escapeSearchTerm($questionUri).' AND selectedchoice:'.$rooloClient->escapeSearchTerm($mcChoice).' AND author:('.$authorsStr.')';

// Retrieve all solutions belong to this runId
$query = 'type:Solution AND owneruri:'.$rooloClient->escapeSearchTerm($questionUri).' AND selectedchoice:'.$rooloClient->escapeSearchTerm($mcChoice).' AND runid:('.$runId.')';
$allSolutions = $rooloClient->search($query, 'metadata', 'latest');

$solutions = array();

//Retrieve all eligible solutions for element histogram
for($i=0; $i<sizeof($allSolutions); $i++){
	$solutionObject = $allSolutions[$i];
	$author = strtolower($solutionObject->author);
	if (strstr($author , $mode) === FALSE){
		array_push($solutions, $allSolutions[$i]);
	}
}
/*
 * Extract category count
 */
$catCounter = array();
$rationales = array();

// An array for storing all solutions categories of specific problem Object
$allCatCounter = array();
foreach (Application::$problemCategories as $curCat){
	$catCounter[$curCat] = 0;
}

// Add dropDown options to the categories array;
foreach (Application::$dropdownsItems as $items){
    for($idx = 1; $idx < sizeof($items);$idx++){
    	$catCounter[$items[$idx]] = 0;
    }
}

foreach ($solutions as $curSolution){
	/*
	 * Get category count
	 */
	if (trim($curSolution->category) != ''){
		$curCats = explode(",", trim($curSolution->category));
		foreach ($curCats as $curCat){
			
			$curCat = trim($curCat);
			
			if ($curCat != '' and $curCat != 'undefined'){
				$catCounter[$curCat] += 1;
			}	
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