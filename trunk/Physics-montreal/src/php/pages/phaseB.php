<?php
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';

//TODO delete later
$_SESSION['groupName'] = $_REQUEST['groupName'];

// get constants
$groupName = $_SESSION['groupName'];
$numProblemsForPhaseB = Application::$numProblemsForPhaseB;
$principleSpecialty = Application::$superGroupSpecialties[$groupName];

$roolo = new RooloClient();



$result = $roolo->search("uri:".$roolo->escapeSearchTerm('roolo://scy.collide.info/scy-collide-server/119.SuperSummary'), "metadata", "latest");

print_r($result);
die();






// make sure they have no summary objects from before 
$summaries = $roolo->search("type:SuperSummary AND author:$groupName", 'metadata', 'latest');

echo "this is ".$_SESSION['groupName']."<br/>";
echo "group's speciality is ".$principleSpecialty."<br/>";
echo sizeof($summaries). " previous summaries were found <br/>";

/**
 * CREATE SUPER SUMMARY OBJECTS IF REQUIRED
 */
if (sizeof($summaries) == 0){
	// find the problems that are in the same specialty as this group
	$problemsQuery = "type:Problem AND principleName:".$roolo->escapeSearchTerm($principleSpecialty);

	$problems = $roolo->search($problemsQuery, 'metadata', 'latest');
	$numProblems = sizeof($problems);
	
	echo "there are $numProblems problems in the $principleSpecialty category";
	
	$summaries = array();
	
	// choose random problems
	$chosenProblems = getRandomNumsBetween($numProblemsForPhaseB, 0, $numProblems-1);
	
	$problemChoices = Application::$problemChoices;

	echo "<br/>";
	echo "<br/>";
	echo "Choose $numProblemsForPhaseB random problems<br/>";	
	// go through each chosen problem 
	// create summary objects in Roolo 
	for ($i=0;$i<sizeof($chosenProblems);$i++){
		$curProblem = $problems[$chosenProblems[$i]];
		
		for ($j=0;$j<sizeof($problemChoices);$j++){
			$curChoice = $problemChoices[$j];
			echo "creating superSummary for ".$curProblem->get_uri()." --> ";
		
			$summary = new SuperSummary();
			$summary->set_author($groupName);
			$summary->set_title('Super Summary of some solutions of problem: ' . $curProblem->get_uri());
			$summary->set_problemUri($curProblem->get_uri());
			$summary->set_forChoice($curChoice);
			$summary->set_summary('');
			
			$addedSummary = $roolo->addElo($summary);
			$addedSummary = new SuperSummary($addedSummary);
			$summaries[] = $addedSummary;
			
			echo $addedSummary->get_uri()."<br/>";
		}
	}
}else{
	/*
	 * FOT TESTING ONLY, DELETE LATER
	 */
	foreach ($summaries as $summary){
		$roolo->deleteElo($summary->get_uri());
	}
	echo "and now all summaries have been deleted";
	/*
	 * END OF TEST CODE
	 */
}

$mainProblems = array();
foreach($summaries as $summary){
	echo $summary->generateXml();
	echo "problem uri == " . $summary->get_problemUri()."<br/>";
	die();
	$mainProblems[$summary->get_problemUri()] = 1;
}

//print_r($summaries);
//print_r($mainProblems);

foreach($mainProblems as $problemUri => $empty){
	echo "<a href='createSummaries.php?problemUri=".$problemUri."'> Create Summary </a><br/>";
}
?>



<?php 
require_once './footer.php';

function getRandomNumsBetween($numOfRandNums, $min, $max){
	$chosenProblems = array();
	while(sizeof($chosenProblems) < $numOfRandNums){
		$randomIdx = rand($min, $max);
		if (!in_array($randomIdx, $chosenProblems)){
			$chosenProblems[] = $randomIdx;
		}
	}
	
	return $chosenProblems;
}
?>