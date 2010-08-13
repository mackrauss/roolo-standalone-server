<?php
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';

//TODO delete later
$_SESSION['groupName'] = $_REQUEST['groupName'];

$groupName = $_SESSION['groupName'];

$principleSpecialty = Application::$superGroupSpecialties[$groupName];
$roolo = new RooloClient();

// make sure they have no summary objects from before
$previousSummaries = $roolo->search("type:SuperSummary AND author:$groupName");

if (sizeof($previousSummaries) == 0){
	// find the problems that are in the same specialty as this group
//	$problemsQuery = "type:Problem AND principleName:$principleSpecialty";
	$problemsQuery = "type:Problem";
	
	$problems = $roolo->search($problemsQuery, "elo", "latest");
	$numProblems = sizeof($problems);
	
	// choose 3 random problems
	$chosenProblems = getRandomNumsBetween(3, 0, $numProblems-1);

	// go through each chosen problem 
	// create summary objects in Roolo 
	for ($i=0;$i<sizeof($chosenProblems);$i++){
		$curProblem = $problems[$chosenProblems[$i]];
		$summary = new SuperSummary();
		$summary->set_author($groupName);
		$summary->set_title('Super Summary of some solutions of problem: ' . $curProblem->get_uri());
		$summary->set_problemUri($curProblem->get_uri());
		$summary->set_summary('');
		
//		$roolo->addElo($summary);
	}
	
	$firstProblemUri = $problems[$chosenProblems[0]]->get_uri();
	echo "<script type='text/javascript'>window.location.href='createSummaries.php?problemUri=$firstProblemUri'</script>";
}else{
	foreach ($previousSummaries as $summary){
		if ($summary->summary == ''){
			$problemUri = $summary->problemUri;
			echo "<script type='text/javascript'>window.location.href='createSummaries.php?problemUri=$problemUri'</script>";
			break;
		}
	}
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