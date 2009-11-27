<?php

require_once '../dataModels/RooloClient.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/Tag.php';

$rooloClient = new RooloClient();

$author = $_GET['username'];
$isMasterSolution = $_GET['masterSolution'] ? $_GET['masterSolution'] : 0;


$categories = split('&', $_GET["categories"]);

$results = '';
// Creating a tag elo. This needs to be changed to a QuestionCategory elo
foreach ($categories as $category){
	list($catKey, $catValue) = split('=', $category);
	$tag = new Tag();
	$tag->set_author($author);
	$tag->set_title($catValue);
	$results .= $rooloClient->addElo($tag);
}

return $results;


?>