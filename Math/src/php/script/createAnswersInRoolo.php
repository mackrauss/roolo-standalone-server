<?php
require_once '../RooloClient.php';
require_once '../dataModels/Question.php';
require_once '../dataModels/QuestionCategory.php';
require_once '../dataModels/UploadedSolution.php';
$rooloClient = new RooloClient();

$questions = $rooloClient->search('type:Question', 'metadata', 'latest');
$teams = array('teamA', 'teamB', 'teamC');

foreach ($questions as $curQuestion){
	for ($i=0; $i< 3; $i++){
		$answer = new UploadedSolution();
		
		$answer->set_author($teams[rand(0, sizeof($teams)-1)]);
		$answer->set_ownerType('Question');
		$answer->set_ownerUri($curQuestion->get_uri());
		$answer->set_path('/some/answer/path.jpg');
		
		$savedAnswer = new UploadedSolution($rooloClient->addElo($answer));
		echo $savedAnswer->get_uri()."<br/>";
	}
}