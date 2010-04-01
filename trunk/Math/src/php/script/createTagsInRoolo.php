<?php
require_once '../RooloClient.php';
require_once '../dataModels/Question.php';
require_once '../dataModels/QuestionCategory.php';
$rooloClient = new RooloClient();

$questions = $rooloClient->search('type:Question', 'metadata', 'latest');
$categories = array('catA', 'catB', 'catC', 'catD');
foreach ($questions as $curQuestion){
	for ($i=0; $i< 7; $i++){
		$tag = new QuestionCategory();
		
		$tag->set_author('teamA');
		$tag->set_ownerType('Question');
		$tag->set_ownerUri($curQuestion->get_uri());
		$tag->set_title($categories[rand(0, sizeof($categories)-1)]);
		$tag->set_questionPath($curQuestion->get_path());
		
		$savedTag = new QuestionCategory($rooloClient->addElo($tag));
		echo $savedTag->get_uri()."<br/>";
	}
}