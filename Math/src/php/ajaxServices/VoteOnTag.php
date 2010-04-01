<?php 
require_once '../RooloClient.php';
require_once '../domLib/simple_html_dom.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/QuestionCategory.php';
require_once '../dataModels/Question.php';
require_once '../graphML/GraphML.php';

$questionUri = $_REQUEST['questionUri'];

$header = $graphML->getHeader();
$footer = $graphML->getFooter();

// -------------------------- update CLASSROOM file 
$graphNodeClassroom = $graphML->getGraphNode('classroom.graphml');
$graphDom = str_get_dom($graphNodeClassroom);

// find the edge with the right ID
$edge = $graphDom->find('edge[id=????]');
$edge = $edge[0];

foreach (QuestionCategory::$categories as $curTag){
	// UPDATE THE EDGES	
}

$graphML->updateFile('classroom.graphml', $newContentClassroom);

// -------------------------- update CATEGORY file
$graphMLnum = '';
	switch ($group){
		
		case 'Geometry':
			$graphNodeCategory = $graphML->getGraphNode('1.graphml');
			$graphMLnum = 1;
			break;
	
		case 'Trigonametry':
			$graphNodeCategory = $graphML->getGraphNode('2.graphml');
			$graphMLnum = 2;
			break;
	
		case 'Exponential':
			$graphNodeCategory = $graphML->getGraphNode('3.graphml');
			$graphMLnum = 3;
			break;
	
		case 'Algebra':
			$graphNodeCategory = $graphML->getGraphNode('4.graphml');
			$graphMLnum = 4;
			break;
	}
	$graphDom = str_get_dom($graphNodeCategory);
	
	/*
	 * UPDATE THE EDGES
	 */
	
	$graphML->updateFile($graphMLnum . '.graphml', $newContentCategory);