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
$problemUri = $_REQUEST['problemUri'];

$roolo = new RooloClient();

$problem = $roolo->retrieveElo($problemUri);
$problemUriEncoded = $roolo->escapeSearchTerm($problemUri);
$solutions = $roolo->search("type:Solution AND owneruri:$problemUriEncoded");
$solutionCategories = array();

foreach ($solutions as $solution){
	$choice = $solution->selectedChoice;
	
	if (!isset($solutionCategories[$choice])){
		$solutionCategories[$choice] = array();  
	}
		
	$solutionCategories[$choice][] = $solution;
}
?>

<?php 
require_once './footer.php';
?>