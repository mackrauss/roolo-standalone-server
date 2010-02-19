<?php
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';


$rooloClient = new RooloClient();

$problems = $rooloClient->search("type:Problem", "metadata", "latest");
$numProblems = sizeof($problems);

$principles = $rooloClient->search("type:Principle", "metadata", "latest");
$numPrinciples = sizeof($principles);

$problemsPerPrinciple = $numProblems / $numPrinciples;
$leftoverProblems = $numProblems % $numPrinciples;

echo "Found $numProblems problems <br/>";
echo "Found $numPrinciples principles <br/>";
echo "So we have $problemsPerPrinciple problems per principle, with $leftoverProblems leftovers <br/>";

echo "<br/>";
echo "<br/>";

$curProblemIndex = 0;
for ($i=0;$i < $numPrinciples;$i++){
	$curPrinciple = $principles[$i];
	for ($j=0;$j < $problemsPerPrinciple; $j++){
		$curProblem = $problems[$curProblemIndex];

		$curProblem->set_principleUri($curPrinciple->get_uri());
		$curProblem->set_principleName($curPrinciple->get_title());

		echo $curProblem->get_uri() . " has been assigned to " . $curPrinciple->get_uri()."<br/>";
		$rooloClient->updateElo($curProblem);
		
		$curProblemIndex++;
	}
}
?>