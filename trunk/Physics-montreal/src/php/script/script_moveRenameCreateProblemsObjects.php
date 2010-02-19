<?php
/*
 * This script is utitility to rename and move all problems submited in sourceDir Directory
 * to destinationDir Directory
 * then makes an Elo Problem Object for each of moved and rename Problem Object in Repository
 */

require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
$rooloClient = new RooloClient();
$problem = new Problem();

//the directory of source questions
//$sourceDir = str_replace('//','/',dirname(__FILE__)."/../../../ProblemsSource");
$sourceDir = str_replace('//','/',dirname(__FILE__)."/../../../problemsSource");

//The directory of destination questions
//$destinationDir = str_replace('//','/', dirname(__FILE__) . '/../../../Problems/');
$destinationDir = str_replace('//','/', dirname(__FILE__) . '/../../../Problems/');

// create handlers for the directoris
$sourceHandler = opendir($sourceDir);

date_default_timezone_set('Canada/Eastern');

$fileCount = "0";
$fileSaved = "0";

while (false !== ($file = readdir($sourceHandler))) {
	if ($file != "." && $file != "..") { 
		//take milliseconds
		list( $msecs, $secs ) = split( ' ', microtime());
		$threeDigitofmsecs = substr(number_format($msecs, 5),2);
		$extention = strrchr($file,'.');
		
		$fileName = date("Y-m-d-H-i-s", $secs) . "-" . $threeDigitofmsecs;
		$newFileName = date("Y-m-d-H-i-s", $secs) . "-" . $threeDigitofmsecs . $extention;
		$sourcePath = $sourceDir."/".$file;
		$destinationPath = $destinationDir.$newFileName;

		if (!copy($sourcePath, $destinationPath)) {
			echo "failed to move" . $file . "...\n";
		}else{
			$curPath = substr($destinationPath, strrpos($destinationPath, '/Problems'));
			$problem->path = $curPath;
			$problem->pathtype = 2;//select 'diskPath' type
			$problem ->title = 'Problem.'.$fileName;
			$problem ->category = '';
			//$problem ->solutionpath = '';
			//$problem ->solutionpathtype = $problem->selectPathType(2);//select 'DISKPath' type
			$rooloClient->addElo($problem);
			$fileSaved = $fileSaved + 1;
		}
		$fileCount = $fileCount + 1;
		
	}
}
	print 'The files have been saved are <b>' . $fileSaved . " / " . $fileCount . '</b><br>'; 
		
	closedir($sourceHandler);
?>