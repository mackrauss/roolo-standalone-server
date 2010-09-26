<?php
session_start();

include_once '../RooloClient.php';
include_once '../dataModels/TeacherProgress.php';
include_once '../dataModels/Problem.php';
include_once '../dataModels/LongProblem.php';

/*
 * Setup variables
 */
$testProblemsPath = '../../../testProblems';
$roolo = new RooloClient();

/*
 * Reset Application
 */
include "./resetApplication.php";

/*
 * create TeacherProgress ELO
 */
$tp = new TeacherProgress();
$tp->set_progress('');
$savedTpXml = $roolo->addElo($tp);

/*
 * create Runs
 */
/********************* V1_C1 ***********************/
$runId = createRun(1, 1, 'C');
createMcProblem($runId, $testProblemsPath.'/v1_c1/v1c1_q1.png', 'B', $roolo);
createMcProblem($runId, $testProblemsPath.'/v1_c1/v1c1_q2.png', 'C', $roolo);
createLongProblem($runId, $testProblemsPath.'/v1_c1/v1c1_longq1.png', $roolo);

/********************* V1_C2 ***********************/
$runId = createRun(1, 2, 'D');
createMcProblem($runId, $testProblemsPath.'/v1_c2/v1c2_q1.png', 'D', $roolo);
createMcProblem($runId, $testProblemsPath.'/v1_c2/v1c2_q2.png', 'A', $roolo);
createLongProblem($runId, $testProblemsPath.'/v1_c2/v1c2_longq1.png', $roolo);

/********************* V2_C1 ***********************/
$runId = createRun(2, 1, 'E');
createMcProblem($runId, $testProblemsPath.'/v2_c1/v2_q1.png', 'C', $roolo);
createMcProblem($runId, $testProblemsPath.'/v2_c1/v2_q2.png', 'E', $roolo);
createMcProblem($runId, $testProblemsPath.'/v2_c1/v2_q3.png', 'B', $roolo);
createLongProblem($runId, $testProblemsPath.'/v2_c1/v2_longq1.png', $roolo);

/********************* V3_C1 ***********************/
$runId = createRun(3, 1, 'F');
createMcProblem($runId, $testProblemsPath.'/v3_c1/v3_q1.png', 'C', $roolo);
createMcProblem($runId, $testProblemsPath.'/v3_c1/v3_q2.png', 'C', $roolo);
createMcProblem($runId, $testProblemsPath.'/v3_c1/v3_q3.png', 'C', $roolo);
createMcProblem($runId, $testProblemsPath.'/v3_c1/v3_q4.png', 'C', $roolo);
createLongProblem($runId, $testProblemsPath.'/v3_c1/v3_longq1.png', $roolo);

/*
 * Find the current number of files in directory
 */
function getNumImagesInDir($target_dir){
//	$target_dir = $appPath.'/problems/'.$runId.'/mc';
	$pattern="(\.jpg$)|(\.png$)|(\.jpeg$)|(\.gif$)";
	$numImages=0;
	
	if($handle = opendir($target_dir)) {
		while(false !== ($file = readdir($handle))){ 
			if(eregi($pattern, $file)){  
				$numImages++; 
			}
		}
	}
	
	return $numImages;
}

function getFileExtension($fileName){
	return substr($fileName, strrpos($fileName, '.') + 1);
}

function createMcProblem($runId, $imagePath, $correctAnswer, $roolo){
	$appPath = dirname(__FILE__).'/../../..';
	
	
	$imageDestWebDir = '/problems/'.$runId.'/mc';
	$imageDestDiskDir = $appPath.$imageDestWebDir;
	
	$numImages = getNumImagesInDir($imageDestDiskDir);
	$imageExtension = getFileExtension($imagePath);
	$imageNewName = "q" . ($numImages+1).'.'.$imageExtension;
	
	// copy problem image to right place
//	$copyCommand = "cp $imagePath ".$imageDestDiskDir.'/'.$imageNewName;
//	`$copyCommand`;
	copy($imagePath, $imageDestDiskDir.'/'.$imageNewName);
	
	// create Problem ELO
	$problem = new Problem();
	$problem->set_author('Teacher');
	$problem->set_runId($runId);
	$problem->path = $imageDestWebDir.'/'.$imageNewName;
	$problem->set_mcmastersolution($correctAnswer);
	
	$roolo->addElo($problem);
}

function createLongProblem($runId, $imagePath, $roolo){
	$appPath = dirname(__FILE__).'/../../..';
	
	
	$imageDestWebDir = '/problems/'.$runId.'/lq';
	$imageDestDiskDir = $appPath.$imageDestWebDir;
	
	$numImages = getNumImagesInDir($imageDestDiskDir);
	$imageExtension = getFileExtension($imagePath);
	$imageNewName = "q" . ($numImages+1).'.'.$imageExtension;
	
	// copy problem image to right place
//	$copyCommand = "cp $imagePath ".$imageDestDiskDir.'/'.$imageNewName;
//	`$copyCommand`;
	copy($imagePath, $imageDestDiskDir.'/'.$imageNewName);
	
	// create Problem ELO
	$problem = new LongProblem();
	$problem->set_author('Teacher');
	$problem->set_runId($runId);
	$problem->path = $imageDestWebDir.'/'.$imageNewName;
	
	$roolo->addElo($problem);
}

function createRun($version, $class, $choiceLimit){
	$_REQUEST['runVersion'] = $version;
	$_REQUEST['runClass'] = $class;
	$_REQUEST['runChoices'] = $choiceLimit;
	ob_start();
	include "./createNewRun.php";
	$response = json_decode(ob_get_clean());
	
	return $response->runId;
}
?>