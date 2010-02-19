<?php
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';


$rooloClient = new RooloClient();

$problems = $rooloClient->search("type:Problem", "elo", "latest");
$numProblems = sizeof($problems);

$principles = $rooloClient->search("type:Principle", "elo", "latest");
$numPrinciples = sizeof($principles);

$problemsPerPrinciple = $numProblems / $numPrinciples;
$leftoverProblems = $numProblems % $numPrinciples;

for ($i=0;$i < $numPrinciples;$i++){
	$curPrinciple = $principles[$i];
	for ($j=0;$j < $problemsPerPrinciple; $j++){
		$curProblem = $problems[$i*$numPrinciples+$j];
		
		$curProblem->set_principleUri($curPrinciple->get_uri());
		$curProblem->set_principleName($curPrinciple->get_title());

		/*
		 * don't know why the hell it doesn't work when updating
		 * Roolo refuses to take in and record the new metdata keys : 
		 * principleUrl and principleName !!!
		 * even when added to the xml config files. 
		 */
		echo $curProblem->generateXml();
//		echo $rooloClient->updateElo($curProblem);
		die();
	}
}

var_dump($results);

?>