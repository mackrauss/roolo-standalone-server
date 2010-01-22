<?php
/*
 * This script is utitility to rename and move all questions submited in sourceDir Directory
 * to destinationDir Directory
 * then makes an Elo Question Object for each of moved and rename questions in Repository
 */

require_once '../RooloClient.php';
require_once '../dataModels/Question.php';
$rooloClient = new RooloClient();
$question = new Question();

//the directory of source questions
$sourceDir = str_replace('//','/',dirname(__FILE__)."/../../../questionsSource");

//The directory of destination questions
$destinationDir = str_replace('//','/', dirname(__FILE__) . '/../../../Questions/');

// create handlers for the directoris
$sourceHandler = opendir($sourceDir);

date_default_timezone_set('Canada/Eastern');

$fileCount = "0";
$fileSaved = "0";
while (false !== ($file = readdir($sourceHandler))) {
	if ($file != "." && $file != ".." && strpos($file, ".") !== 0) {
		//take milliseconds
		list( $msecs, $secs ) = split( ' ', microtime());
		$threeDigitofmsecs = substr(number_format($msecs, 5),2);
		$extention = strrchr($file,'.');
		
		$fileName = date("m-d-H-i-s", $secs) . "-" . $threeDigitofmsecs;
		$newFileName = date("m-d-H-i-s", $secs) . "-" . $threeDigitofmsecs . $extention;
		$sourcePath = $sourceDir."/".$file;
		$destinationPath = $destinationDir.$newFileName;
//		echo "</br>sourceDir = ".$sourceDir."/".$file;
//		echo "</br>Destination = ".$destinationDir.$newFileName;
		//if (!copy($sourceDir."/".$file, $destinationDir.$newFileName)) {
		if (!rename($sourcePath, $destinationPath)) {
			echo "failed to move $file...\n";
		}else{
			//$curPath = $paths[$i];
			$curPath = substr($destinationPath, strrpos($destinationPath, '/Questions'));
			$question->set_path($curPath);
			
			$question->set_subType("Math");
			
			//$question->set_author('');
			//$question->set_datecreated('');
			//$question->set_datelastmodified('');
			$question->set_title('question_' . $fileName);
			//$question->set_uri('');
			//$question->set_version('');
			$question->set_masterSoulution('null'); 
			//echo $rooloClient->addElo($question);
			$rooloClient->addElo($question);
			
			$fileSaved = $fileSaved + 1;
		}
		$fileCount = $fileCount + 1;
		
	}
}
	print 'The files have been saved. ' . $fileSaved . " / " . $fileCount . '</br>\n'; 
		
	closedir($sourceHandler);
?>