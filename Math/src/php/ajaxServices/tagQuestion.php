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

$author = trim($_GET['author']);
$role = trim($_GET['role']);
$isMasterSolution = trim($_GET['masterSolution']) ? trim($_GET['masterSolution']) : 0;
$category = trim($_GET["tags"]);
$path = trim($_GET['path']);
$ownerURI = trim($_GET['ownerURI']);

//user is a teacher
if($role == 'teacher'){
	echo 'uri = '.$ownerURI;
	$question = new Question();
	$question = $rooloClient->retrieveElo($ownerURI);
	//$question->set_uri($ownerURI);
	//$question->set_subType($subType);
	$question->set_author($author);
	//$question->set_datecreated('');
	//$question->set_datelastmodified('');
	//$question->set_title('question_'.($i + 1));
	$question->set_masterSoulution($category);
	
	$result = $rooloClient->updateElo($question);
//	echo "result = " .$result;
//	die();
}else{
	if ($role == 'student'){
		$unansweredQuestions = '';
		//create a questionCategory Object
		$questionCategory = new QuestionCategory();
		$questionCategory->set_author($author);
		$questionCategory->set_title($category);
//		if ($isMasterSolution){
//			$questionCategory->set_asMasterSolution();
//		}
//		$questionCategory->set_questionPath($path);
		$questionCategory->set_ownerUri($ownerURI);
		$unansweredQuestions .= $rooloClient->addElo($questionCategory);
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
	
	case 'functions':
		$graphNodeCategory = $graphML->getGraphNode('1.graphml');
		$graphMLnum = 1;
		break;

	case 'trigonometry':
		$graphNodeCategory = $graphML->getGraphNode('2.graphml');
		$graphMLnum = 2;
		break;

	case 'graphing':
		$graphNodeCategory = $graphML->getGraphNode('3.graphml');
		$graphMLnum = 3;
		break;

	case 'algebra':
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