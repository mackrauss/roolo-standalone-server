<?php 
session_start();

header('Content-type: text/xml');
$runId = $_REQUEST['runId'];
$scope = $_REQUEST['scope'];
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
 * Get all answers (with the right scope) to this problem
 */
$scopeCondition = $scope == 'ind' ? '!author:group*' : 'author:group*';
$solutionsQuery = "type:Solution AND  $scopeCondition AND owneruri:".$rooloClient->escapeUri($problem->uri);
$solutions = $rooloClient->search($solutionsQuery, 'metadata', 'latest');

/*
 * Get all the elements in the application
 */
$elements = Application::$problemCategories;
$dropdownElements = Application::$dropdownsItems;
foreach ($dropdownElements as $curDropdownName => $curDropdownElements){
	unset($curDropdownElements[0]);
	$elements = array_merge($elements, $curDropdownElements);
}

/*
 * Create the $elementsAnswersMap from the elements and solutions
 */
$elementAnswersMap = array();
foreach ($elements as $curElement){
	$elementAnswersMap[$curElement] = array(
											'A' => 0, 
											'B' => 0,
											'C' => 0,
											'D' => 0);
}

foreach ($solutions as $curSolution){
	$curSolutionElements = explode(',', $curSolution->category);
	$curSolutionLetter = $curSolution->selectedchoice;
	foreach ($curSolutionElements as $curSolutionElement){
		$curSolutionElement = trim($curSolutionElement);
		$elementAnswersMap[$curSolutionElement][$curSolutionLetter] = $elementAnswersMap[$curSolutionElement][$curSolutionLetter]+1; 
	}
}

?>
<graphml xmlns='http://graphml.graphdrawing.org/xmlns' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd'>
	<key id='dn0' for='node' attr.name='elements' attr.type='string' />
	<key id='dn1' for='node' attr.name='A' attr.type='int' />
	<key id='dn2' for='node' attr.name='B' attr.type='int'/>
	<key id='dn3' for='node' attr.name='C' attr.type='int'/>
	<key id='dn4' for='node' attr.name='D' attr.type='int'/>
		
	<graph id='G' edgedefault='undirected'>
<?php
$loopCount=1;
foreach ($elementAnswersMap as $elementName => $answerLetterMap){
?>
	<node id='<?= $loopCount ?>'>
		<data key='dn0'><?= $elementName ?></data>
		<data key='dn1'><?= $answerLetterMap['A']?></data>
		<data key='dn2'><?= $answerLetterMap['B']?></data>
		<data key='dn3'><?= $answerLetterMap['C']?></data>
		<data key='dn4'><?= $answerLetterMap['D']?></data>
	</node>	
<?php
	$loopCount++;
}
?>	
	</graph>
</graphml>