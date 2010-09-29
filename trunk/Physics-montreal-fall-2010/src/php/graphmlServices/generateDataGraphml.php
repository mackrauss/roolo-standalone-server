<?php 
session_start();

header('Content-type: text/xml');
$runId = $_REQUEST['runId'];
$scope = $_REQUEST['scope'];

require_once '../RooloClient.php';
require_once '../Application.php';
require_once '../CommonFunctions.php';

$rooloClient = new RooloClient();

$problemsQuery = "type:Problem AND runid:".$runId;
$problems = $rooloClient->search($problemsQuery, 'metadata', 'latest');
	
?>

<graphml xmlns='http://graphml.graphdrawing.org/xmlns' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://graphml.graphdrawing.org/xmlns http://graphml.graphdrawing.org/xmlns/1.0/graphml.xsd'>

	<key id='dn0' for='node' attr.name='type' attr.type='string' />
	<key id='dn1' for='node' attr.name='group' attr.type='string' />
	<key id='dn2' for='node' attr.name='category' attr.type='string' />
	<key id='dn3' for='node' attr.name='tags' attr.type='int' />
	<key id='dn4' for='node' attr.name='img' attr.type='string' />
	<key id='de0' for='edge' attr.name='group' attr.type='string' />
	<key id='de1' for='edge' attr.name='agree' attr.type='int'> 

		<default>0</default>
	</key>
	<key id='de2' for='edge' attr.name='disagree' attr.type='int'>
		<default>0</default>
	</key>
	<graph id='G' edgedefault='undirected'>
		<node id='problems'>
			<data key='dn0'>root</data>
			<data key='dn1'>problems</data>
			<data key='dn2'></data>
		</node>

<!-- Generating the problem nodes -->
<?php
for ($i=0; $i < sizeof($problems); $i++){
	
	$curProblem = $problems[$i];
	$imageName = basename($curProblem->path);
	$problemNodeId = substr($imageName, 0, strpos($imageName, ".")); 
?>
<node id='<?= $problemNodeId?>'>
	<data key='dn0'>problem</data>
	<data key='dn1'><?= $problemNodeId?></data>
	<data key='dn2'>1</data>
	<data key='dn3'>0</data>
	<data key='dn4'><?= $imageName?></data>
</node>
<?php 
}
?>

<!-- Generating the problem edges to the root Problem node -->
<?php 
for ($i=0; $i < sizeof($problems); $i++){
	
	$curProblem = $problems[$i];
	$imageName = basename($curProblem->path);
	$problemNodeId = substr($imageName, 0, strpos($imageName, "."));
	$edgeId = $problemNodeId.'-problems';
?>
<edge id='<?= $edgeId ?>' source='<?= $problemNodeId ?>' target='problems'>
	<data key='de0'></data>
	<data key='de1'></data>
	<data key='de2'></data>
</edge> 
<?php 
}
?>

<!-- Generating all the edges between problems and their respective elements. Each edge also gets a unique element node-->
<?php 
/*
 * We're going to iterate through all problems in this run, and for each,  
 * get all the solutions within this specified scope (ind | grp) and extract
 * their elements. 
 */
$problemIdx = 1;
foreach ($problems as $curProblem){
	$problemUri = $curProblem->get_uri();
	if ($scope == 'ind'){
		$solutionsQuery = "type:Solution AND !author:group* AND runid:$runId AND owneruri:".$rooloClient->escapeSearchTerm($problemUri);
	}else{
		$solutionsQuery = "type:Solution AND author:group* AND runid:$runId AND owneruri:".$rooloClient->escapeSearchTerm($problemUri);
	}
	
	$solutions = $rooloClient->search($solutionsQuery, 'metadata', 'latest');
	$imageName = basename($curProblem->path);
	$curProblemNodeId = substr($imageName, 0, strpos($imageName, "."));
	
	$elementsCountMap = array();
	foreach ($solutions as $curSolution){
		$curElements = explode(",",$curSolution->category);
		 
		foreach ($curElements as $curElement){
			$convertedElementId = convertToId($curElement);
			
			if (!isset($elementsCountMap[$convertedElementId])){
				$elementsCountMap[$convertedElementId] = array('count' => 1, 'name' => trim($curElement));
			}else{
				$elementsCountMap[$convertedElementId]['count']++;
			}
		}
	}
	
	foreach ($elementsCountMap as $elementId => $elementData){
		$elementCount = $elementData['count'];
		$elementName = $elementData['name'];
		$elementNodeId = $elementId.$problemIdx;
		$edgeId = $curProblemNodeId.'-'.$elementNodeId;
?>
<node id='<?= $elementNodeId ?>'>
	<data key='dn0'>group</data>
	<data key='dn1'><?= $elementName ?></data>
	<data key='dn2'></data>
</node>

<edge id='<?= $edgeId?>' source='<?= $curProblemNodeId?>' target='<?= $elementNodeId ?>'>
	<data key='de0'></data>
	<data key='de1'><?= $elementCount ?></data>
	<data key='de2'>0</data>
</edge>
<?php
	}
	
	$problemIdx++;
}
?>
	</graph>
</graphml>  