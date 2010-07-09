<?php 
require_once '../RooloClient.php';
require_once '../dataModels/Solution.php';
require_once '../Application.php';

error_reporting(E_ALL | E_STRICT);

$rooloClient = new RooloClient();
$curentSolution = new Solution();


$questionUri = '';
$mcChoice = '';
/*
 * get problemUri param
 */
if (isset($_REQUEST['problemUri'])){
	$questionUri = $_REQUEST['problemUri'];
}else{
	return "ERROR: problemUri param not provided";
}

/*
 * get mcChoice param
 */
if (isset($_REQUEST['mcChoice'])){
	$mcChoice = $_REQUEST['mcChoice'];
}else{
	return "ERROR: mcChoice param not provided";
}

/**
 * Find all the solutions 
 */
$solutions = $rooloClient->search('type:Solution AND owneruri:'.$rooloClient->escapeSearchTerm($questionUri).' AND selectedchoice:'.$rooloClient->escapeSearchTerm($mcChoice), 'metadata', 'latest');

/*
 * Extract category count
 */
$catCounter = array();
foreach (Application::$problemCategories as $curCat){
	$catCounter[$curCat] = 0;
}
foreach ($solutions as $curSolution){
	if (trim($curSolution->category) != ''){
		$curCats = explode(",", trim($curSolution->category));
		foreach ($curCats as $curCat){
			$curCat = trim($curCat);
			
			$catCounter[$curCat] += 1;	
		}
	}
}

$catCounterJson = json_encode($catCounter);

echo $catCounterJson;
?>