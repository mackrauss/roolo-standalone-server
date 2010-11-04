<?php 
session_start();

header('Content-type: text/xml');

require_once '../RooloClient.php';
require_once '../Application.php';
require_once '../CommonFunctions.php';

/*
 * Get params
 */
$runId = $_REQUEST['runId'];
$scope = $_REQUEST['scope'];
$problemName = $_REQUEST['qId'];


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
 * Get all answers (with the right scope) to this problem
 */
$scopeCondition = $scope == 'ind' ? '!author:group*' : 'author:group*';
$solutionsQuery = "type:Solution AND  $scopeCondition AND owneruri:".$rooloClient->escapeUri($problem->uri);
$solutions = $rooloClient->search($solutionsQuery, 'metadata', 'latest');

/*
 * Generate $answerMap (answerLetter => answerCount)
 */
$answerMap = array();
foreach ($solutions as $curSolution){
	$curSolutionLetter = $curSolution->selectedchoice;
	
	$answerMap[$curSolutionLetter] = isset($answerMap[$curSolutionLetter]) ? $answerMap[$curSolutionLetter] + 1 : 1;
}

/*
 * what's the correct answer to the problem?
 */
$correctAnswerLetter = $problem->mcmastersolution;
?>
<graphml xmlns='http://graphml.graphdrawing.org/xmlns' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd'>
	<key id='dn0' for='node' attr.name='answer' attr.type='string' />
	<key id='dn1' for='node' attr.name='frequency' attr.type='int' />
	<key id='dn2' for='node' attr.name='correct' attr.type='int'/>
		
	<graph id='G' edgedefault='undirected'>

<?php 
$loopCount = 1;
$letters = array('A', 'B', 'C', 'D', 'E');
foreach ($letters as $curLetter){
	$answerCount = isset($answerMap[$curLetter]) ? $answerMap[$curLetter] : 0;
	$answerIsCorrect = $correctAnswerLetter == $curLetter ? '1' : '0';
?>

	<node id='<?= $loopCount ?>'>
		<data key='dn0'><?= $curLetter ?></data>
		<data key='dn1'><?= $answerCount?></data>
		<data key='dn2'><?= $answerIsCorrect?></data>
	</node>

<?php
	$loopCount++; 
}
?>

	</graph>
</graphml>




