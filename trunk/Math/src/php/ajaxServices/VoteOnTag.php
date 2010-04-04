<?php 
require_once '../RooloClient.php';
require_once '../domLib/simple_html_dom.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/QuestionCategory.php';
require_once '../dataModels/Question.php';
require_once '../dataModels/VoteOnTag.php';
require_once '../graphML/GraphML.php';

$rooloClient = new RooloClient();
$graphML = new GraphML();

$author = trim($_REQUEST['author']);
$path = trim($_REQUEST['path']);
$ownerURI = trim($_REQUEST['ownerURI']);
$_REQUEST['votes'] = str_replace("'", "", trim($_REQUEST['votes']));
$_REQUEST['votes'] = substr($_REQUEST['votes'], 0, strrpos($_REQUEST['votes'], ','));
$votesTokens = explode(',', $_REQUEST['votes']);
foreach ($votesTokens as $voteToken) {
	$voteStr = explode(':', $voteToken);
	$votes[$voteStr[0]] = $voteStr[1];
}


// Save all the votes in Roolo
foreach ($votes as $tag => $answer){
	$vote = new VoteOnTag();
	$vote->set_author($author);
	$vote->set_ownerType('Question');
	$vote->set_ownerUri($ownerURI);
	$vote->set_path($path);
	$vote->set_tag($tag);
	if ($answer){
		$vote->set_answer(1);
	}else {
		$vote->set_answer(0);
	}
	$rooloClient->addElo($vote);	
}


$header = $graphML->getHeader();
$footer = $graphML->getFooter();

$graphNodeClassroom = $graphML->getGraphNode('classroom.graphml');
$classroomGraphDom = str_get_dom($graphNodeClassroom);

// we need to extract the question name from the uri
$questionName = substr($path, strrpos($path, '/') +1);
$questionName = substr($questionName, 0, strrpos($questionName, '.'));

// loop through all the votes and update their corresponding edge in the xml file
foreach ($votes as $tag => $answer){
	$edgeId = $questionName . '-' . trim($tag);
	$edge = $classroomGraphDom->find("edge[id=$edgeId]");
	$edge = $edge[0];
	$voteCount = 0;
	if ($answer == 'true'){
		$voteCount = $edge->find('data[key=de1]');
	}else {
		$voteCount = $edge->find('data[key=de2]');
	}
	$voteCount = $voteCount[0];
	$count = $voteCount->innertext();
	$voteCount->innertext = $count+1;
}
$graphML->updateFile('classroom.graphml', $header . $classroomGraphDom . $footer);

