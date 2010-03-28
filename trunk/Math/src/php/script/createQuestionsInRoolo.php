<?php

require_once '../RooloClient.php';
require_once '../dataModels/Question.php';
$rooloClient = new RooloClient();

//The directory of destination questions
$questionDir = dirname(__FILE__) . '/../../../questionsSource/';

// create handlers for the directoris
$sourceHandler = opendir($questionDir);

date_default_timezone_set('Canada/Eastern');

$fileSaved = 0;
while (false !== ($file = readdir($sourceHandler))) {
	if ($file != "." && $file != ".." && $file != ".svn") { 
		
		$questionPath = '/questionsSource/'.$file;
		$fileName = basename($questionPath);

		$question = new Question();
		$question->set_path($questionPath);
		$question->set_subType("Math");
		
		$question->set_title($fileName);
		$question->set_masterSoulution('null'); 
		$rooloClient->addElo($question);
		
		$fileSaved += 1;
	}
}
	print 'The files have been saved. ' . $fileSaved . '</br>\n'; 
		
	closedir($sourceHandler);
?>