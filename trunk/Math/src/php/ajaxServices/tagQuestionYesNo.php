<?php

require_once '../RooloClient.php';
require_once '../domLib/simple_html_dom.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/Tag.php';
require_once '../dataModels/QuestionCategory.php';
require_once '../dataModels/Question.php';
require_once '../graphML/GraphML.php';


$graphML = new GraphML();

$rooloClient = new RooloClient();

$author = trim($_REQUEST['author']);
// We need to extract the group based on the user name. 
// For exmample a user id can be Algebra1, which means they are in the Algebra group
$group = null;

if (strstr($author, 'algebra') > -1){
	$group = 'Algebra';
}else if (strstr($author, 'exponential') > -1){
	$group = 'Exponential';
}else if (strstr($author, 'geometry') > -1){
	$group = 'Geometry';
}else if (strstr($author, 'trigonometry') > -1){
	$group = 'Trigonometry';
}

$role = trim($_REQUEST['role']);
$isMasterSolution = trim($_GET['masterSolution']) ? trim($_GET['masterSolution']) : 0;
$path = trim($_REQUEST['path']);
$ownerURI = trim($_REQUEST['ownerURI']);
$answer = $_REQUEST['answer'];

$fileName = basename($path);

// We need to get the question and update its tags and the total number of tags
// it has.
$question = $rooloClient->retrieveElo($ownerURI);
$curTags = $question->get_tags();
if ($curTags == null){
	$curTags = '';
}
$curNumTags = $question->get_numTags();
if (strstr($curTags, $group) == false){
	$curTags .= ' ' . $group;
	$question->set_tags($curTags);
	$question->set_numTags($curNumTags+1);
	$rooloClient->updateElo($question);
}


//user is a teacher
if($role == 'teacher'){
	$question = $rooloClient->retrieveElo($ownerURI);
	$question->set_author($author);
	$question->set_title($fileName);
	$question->set_masterSoulution($group);
	
	$result = $rooloClient->updateElo($question);
}else{
	if ($role == 'student'){
		$results = '';
		//create a questionCategory Object
		$questionCategory = new QuestionCategory();
		$questionCategory->set_author($author);
		$questionCategory->set_title($group);
		$questionCategory->set_questionPath($path);
		$questionCategory->set_ownerUri($ownerURI);
		$results .= $rooloClient->addElo($questionCategory);
	}else{
		echo "The user is not teacher or student!!!!!";
	}
}

// we need to extract the question name from the full URI
$questionName = substr($path, strrpos($path, '/') +1);
$questionName = substr($questionName, 0, -4);



// need to extract the current node's id to add to the graphml files
// if it's not already there
$curNode = $graphML->buildGraphMLNode('problem', $questionName, $path);
$curNodeDom = str_get_dom($curNode);
$curNodeDom = $curNodeDom->find('node');
$curNodeDom = $curNodeDom[0];
$curNodeId = $curNodeDom->getAttribute('id');

if ($answer === 'YES') {

	// getting the submitted edge
	$curEdge = $graphML->buildGraphMLEdge($questionName, $group);
	
	
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
}
?>