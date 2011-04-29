<?php 
session_start();

header('Content-type: text/xml');
$runId = $_REQUEST['runId'];
$problemName = $_REQUEST['qId'];

require_once '../RooloClient.php';
require_once '../Application.php';
require_once '../CommonFunctions.php';

$rooloClient = new RooloClient();

/*
 * Get the problem ELO
 */
$problemsQuery = "type:Problem AND runid:$runId AND path:".$rooloClient->escapeSearchTerm("/problems/$runId/mc/$problemName.")."*";
$problems = $rooloClient->search($problemsQuery,'metadata', 'latest');

if (count($problems) != 1){
	echo "could not find a single Problem ELO with query: $problemsQuery";
	var_dump($problems);
	die();
}

$problem = $problems[0];

/*
 * Get all answers to this problem
 */
$solutionsQuery = "type:Solution AND owneruri:".$rooloClient->escapeUri($problem->uri);
$solutions = $rooloClient->search($solutionsQuery, 'metadata', 'latest');

echo '<?xml version="1.0"?>'."\n";
echo "<xml>\n";
foreach ($solutions as $curSolution){
	$curRationale = $curSolution->rationale;
	
	echo "\t<rationale student='Ali'>".htmlspecialchars($curRationale)."</rationale>\n";
}
echo "</xml>";
