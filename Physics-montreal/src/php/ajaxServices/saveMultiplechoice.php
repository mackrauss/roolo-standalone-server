<?php


require_once '../RooloClient.php';
require_once '../domLib/simple_html_dom.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/Solution.php';
require_once '../dataModels/Concept.php';
require_once '../graphML/GraphML.php';

$username = trim($_REQUEST['username']);
$selectedChoice = trim($_REQUEST['choice']);
$ownerURI = trim($_REQUEST['ownerURI']);
$rationaleText = trim($_REQUEST['rationale']);
$problemPath = trim($_REQUEST['questionPath'])	;
$category = $_REQUEST['category'];

$rooloClient = new RooloClient();

//create solution Object
$solutionObject = new Solution();

$solutionObject->solutiontype = 0; // 0 means multipleChoice
$solutionObject->author = $username;
$solutionObject->selectedchoice = $selectedChoice;
$solutionObject->owneruri = $ownerURI;

$rationaleTag = $solutionObject->buildContentElement($rationaleText, "rationale");
$solutionObject->content = $rationaleTag;

$solutionObject->title = "Solution-Multiplechoice";
$solutionObject->category = $category;

$results = $rooloClient->addElo($solutionObject);

////create concept Object
//$conceptObject = new Concept();
//
////make and add concept object for friction
//$conceptObject->set_author($username);
////$conceptObject->set_title($friction);
//$conceptObject->set_ownerUri($ownerURI);
//$results = $rooloClient->addElo($conceptObject);
//
////make and add concept object for body
//$conceptObject->set_title($body);
//$results = $rooloClient->addElo($conceptObject);
//
////make and add concept object for acceleration
//$conceptObject->set_title($acceleration);
//$conceptObject->set_ownerUri($ownerURI);
//
//$results = $rooloClient->addElo($conceptObject);


//************************************** cteating Graph file
//$graphML = new GraphML();
//
//// we need to extract the question name from the full URI
//$problemName = substr($ownerURI, strrpos($ownerURI, '/') +1);
//$problemName = substr($problemName, 0, -4);
//
////extract the problem node's idd
//$problemNode = $graphML->buildGraphMLNode('problem', $problemName, $problemPath);
//$problemNodeDom = str_get_dom($problemNode);
//$problemNodeDom = $problemNodeDom->find('node');
//$problemNodeDom = $problemNodeDom[0];
//$problemNodeId = $problemNodeDom->getAttribute('id');
//
//// getting the submitted edge
//$frictionEdge = $graphML->buildGraphMLEdge($problemName, $friction."-".$problemName);
//$bodyEdge = $graphML->buildGraphMLEdge($problemName, $body."-".$problemName);
//$accelerationEdge = $graphML->buildGraphMLEdge($problemName, $acceleration."-".$problemName);
//
////echo $frictionEdge;
////echo $bodyEdge;
////echo $accelerationEdge;
//
//$header = $graphML->getHeader();
//$footer = $graphML->getFooter();
//
//// ----------------------------------- Update the Classroom.graphml file ------------------------------------
//
//// writing the node and the edge classroom file
//$graphNodeClassroom = $graphML->getGraphNode('classroom.graphml');
//
////echo "graphNodeClassroom = " . $graphNodeClassroom;
//
//// Extracting all the node elements. 
//$existingNodes = array();
//$graphDom = str_get_dom($graphNodeClassroom);
//$nodes = $graphDom->find('node');
//foreach ($nodes as $node){
//	$existingNodes[] = $node->getAttribute('id'); 
//}
//
////if the problem node does not exist add problem node as well as 
//// make and add 6 nodes related to problem node.
//if (!in_array($problemNodeId, $existingNodes)){ 
//
//	$graphNodeClassroom .= $problemNode;	
//	
//	//make the friction node
//	$frictionNodeName = "friction-".$problemName;
//	$frictionNode = $graphML->buildGraphMLNode('friction', $frictionNodeName);
//	$graphNodeClassroom .= $frictionNode;	
//	
//	//make the non friction node
//	$nonFrictionNodeName = "~friction-".$problemName;
//	$nonFrictionNode = $graphML->buildGraphMLNode('nonFriction', $nonFrictionNodeName);
//	$graphNodeClassroom .= $nonFrictionNode;	
//	
//	//make the one body node
//	$bodyNodeName = "one body-".$problemName;
//	$bodyNode = $graphML->buildGraphMLNode('oneBody', $bodyNodeName);
//	$graphNodeClassroom .= $bodyNode;	
//	
//	//make the two body node
//	$twoBodyNodeName = "two bodies-".$problemName;
//	$twoBodyNode = $graphML->buildGraphMLNode('towBodies', $twoBodyNodeName);
//	$graphNodeClassroom .= $twoBodyNode;	
//	
//	//make the constant acceleration node
//	$constAccelerationNodeName = "const accel-".$problemName;
//	$constAccelerationNode = $graphML->buildGraphMLNode('constAcceleration', $constAccelerationNodeName);
//	$graphNodeClassroom .= $constAccelerationNode;	
//		
//	//make the non constant acceleration node
//	$nonConstAccelerationNodeName = "~const accel-".$problemName;
//	$nonConstAccelerationNode = $graphML->buildGraphMLNode('nonConstAcceleration', $nonConstAccelerationNodeName);
//	$graphNodeClassroom .= $nonConstAccelerationNode;	
//}
//
//$graphNodeClassroom .= $frictionEdge;
//$graphNodeClassroom .= $bodyEdge;
//$graphNodeClassroom .= $accelerationEdge;
//$newContentClassroom = $header . $graphNodeClassroom . $footer;
//echo $newContentClassroom;
//$graphML->updateFile('classroom.graphml', $newContentClassroom);
?>