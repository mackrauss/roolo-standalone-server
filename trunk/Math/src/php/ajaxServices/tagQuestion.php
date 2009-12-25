<?php

require_once '../RooloClient.php';
require_once '../domLib/simple_html_dom.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/Tag.php';
require_once '../dataModels/QuestionCategory.php';
require_once '../graphML/GraphML.php';


$graphML = new GraphML();

$rooloClient = new RooloClient();

$author = trim($_GET['author']);
$isMasterSolution = trim($_GET['masterSolution']) ? trim($_GET['masterSolution']) : 0;
$category = trim($_GET["tags"]);
$ownerURI = trim($_GET['ownerURI']);

// we need to extract the question name from the full URI
$questionName = substr($ownerURI, strrpos($ownerURI, '/') +1);
$questionName = substr($questionName, 0, -4);

$results = '';
// Creating a tag elo. This needs to be changed to a QuestionCategory elo
//$tag = new Tag();
//$tag->set_author($author);
//$tag->set_title($category);

$questionCategory = new QuestionCategory();
$questionCategory->set_author($author);
$questionCategory->set_title($category);
if ($isMasterSolution){
	$questionCategory->set_asMasterSolution();
}
$questionCategory->set_questionPath($ownerURI);
$results .= $rooloClient->addElo($questionCategory);


// need to extract the current node's id to add to the graphml files
// if it's not already there
$curNode = $graphML->buildGraphMLNode('problem', $questionName, $ownerURI);
$curNodeDom = str_get_dom($curNode);
$curNodeDom = $curNodeDom->find('node');
$curNodeDom = $curNodeDom[0];
$curNodeId = $curNodeDom->getAttribute('id');

// getting the submitted edge
$curEdge = $graphML->buildGraphMLEdge($questionName, $category);


$header = $graphML->getHeader();
$footer = $graphML->getFooter();

// ----------------------------------- Update the Classroom.graphml file ------------------------------------

// writing the node and the edge classroom file
$graphNodeClassroom = $graphML->getGraphNode('classroom.graphml');

// Extracting all the node elements in the specified category file. 
// If the node does not exist, we add it to both the category graphml file
// as well as the classroom file.
$existingNodes = array();
$graphDom = str_get_dom($graphNodeClassroom);
$nodes = $graphDom->find('node');
foreach ($nodes as $node){
	$existingNodes[] = $node->getAttribute('id'); 
}

if (!in_array($curNodeId, $existingNodes)){ // Add node only if it does not already exist
	$graphNodeClassroom .= $curNode;	
}

// UPDATE classroom graphml file
$graphNodeClassroom .= $curEdge;
$newContentClassroom = $header . $graphNodeClassroom . $footer;
$graphML->updateFile('classroom.graphml', $newContentClassroom);


// ----------------------------------- Update the specific CATEGORY graphml file ------------------------------------


$graphMLnum = '';
switch ($category){
	
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


// Extracting all the node elements in the specified category file. 
// If the node does not exist, we add it to both the category graphml file
// as well as the classroom file.
$existingNodes = array();
$graphDom = str_get_dom($graphNodeCategory);
$nodes = $graphDom->find('node');
foreach ($nodes as $node){
	$existingNodes[] = $node->getAttribute('id'); 
}

if (!in_array($curNodeId, $existingNodes)){ // Add node only if it does not already exist
	$graphNodeCategory .= $curNode;
}


// UPDATE category graphml file
$graphNodeCategory .= $curEdge;
$newContentCategory = $header . $graphNodeCategory . $footer;
$graphML->updateFile($graphMLnum . '.graphml', $newContentCategory);

//return $results;




?>