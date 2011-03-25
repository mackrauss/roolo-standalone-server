<?php
/*
 * 1_ reads all problems object from repository and count them
 * 2_ renames and moves all problems image file submited in problemsSource Directory to Problems Directory
 * 3_ makes an Elo Problem Object in Repository
 *   
 * The problem image file new name format containes
 * 		a_Rename date of prblem
 * 		b_the leter of 'q'
 * 		c_the number of that problem in repository   
 */

require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';

$rooloClient = new RooloClient();
$problem = new Problem();

$fileCount = "0";
$fileSaved = "0";

//the directory of source questions
$sourceDir = str_replace('//','/',dirname(__FILE__)."/../../../problemsSource");

//The directory of destination questions
$destinationDir = str_replace('//','/', dirname(__FILE__) . '/../../../Problems/');

// create handlers for the directoris
$sourceHandler = opendir($sourceDir);

date_default_timezone_set('Canada/Eastern');

// retrieve questions from repository

$query = 'type:Problem';
$allProblems = $rooloClient->search($query, 'metadata', 'latest');
$counter = sizeof($allProblems);

while (false !== ($file = readdir($sourceHandler))) {
	if ($file != "." && $file != "..") { 
		//take milliseconds
		list( $msecs, $secs ) = split( ' ', microtime());
		$threeDigitofmsecs = substr(number_format($msecs, 5),2);
		$extention = strrchr($file,'.');
		
		//$fileName = date("Y-m-d-H-i-s", $secs) . "-" . $threeDigitofmsecs. "_P".++$counter;
		$fileName = date("Y_m_d_H_i_s", $secs) . "_" . $threeDigitofmsecs. "_P".++$counter;
		$sourcePath = $sourceDir."/".$file;
		$destinationPath = $destinationDir.$fileName.$extention;

		if (!copy($sourcePath, $destinationPath)) {
			echo "failed to move" . $file . "...\n";
		}else{
			$curPath = substr($destinationPath, strrpos($destinationPath, '/Problems'));
			$problem->path = $curPath;
			$problem->pathtype = 2;//select 'diskPath' type
			$problem ->title = 'Problem.'.$fileName;
			$problem ->category = '';
			$rooloClient->addElo($problem);
			$fileSaved = $fileSaved + 1;
		}
		$fileCount = $fileCount + 1;
		
	}
}
	print 'The files have been saved are <b>' . $fileSaved . " / " . $fileCount . '</b><br>'; 
		
	closedir($sourceHandler);
?>