<?php 
session_start();

require_once '../RooloClient.php';
require_once '../dataModels/RunConfig.php';
require_once '../Application.php';

error_reporting(E_ALL | E_STRICT);

$rooloClient = new RooloClient();

$runVersion = $_REQUEST['runVersion'];
$runClass = $_REQUEST['runClass'];
$runChoices = $_REQUEST['runChoices'];

$generatedRunId = 'v'.$runVersion.'_c'.$runClass;

$response = array();

/*
 * First check to make sure a run with the same RunId doesn't exist. 
 * If it does, send an error.
 */
$query = 'type:RunConfig AND runid:'.$generatedRunId;
$existingRuns = $rooloClient->search($query, 'metadata', 'latest');
if (count($existingRuns) != 0){
	$response['error'] = 'A run with the same configuration alread exists. Please delete that one first!';
	echo json_encode($response);
	die();
}

/*
 * Create new run configuration ELO
 */
$runConfig = new RunConfig();
$runConfig->runVersion = $runVersion;
$runConfig->runClass = $runClass;
$runConfig->runId = $generatedRunId;
$runConfig->runChoiceLimit = $runChoices;
$runConfig->runPublished = 0;
$runConfig->author = $_SESSION['username'];


$rooloClient->addElo($runConfig);
$runId = $runConfig->runId;

$projectRoot = dirname(__FILE__).'/../../../';
/*
 * Create Question directories
 */
if (!is_dir($projectRoot.'/problems/'.$runId)){
	mkdir($projectRoot.'/problems/'.$runId);
	mkdir($projectRoot.'/problems/'.$runId.'/mc');
	mkdir($projectRoot.'/problems/'.$runId.'/lq');
}

/*
 * Create graphML directories
 */
if (!is_dir($projectRoot.'/graphml/'.$runId)){
	mkdir($projectRoot.'/graphml/'.$runId);
	mkdir($projectRoot.'/graphml/'.$runId.'/elements');
	mkdir($projectRoot.'/graphml/'.$runId.'/answers');
}


/*
 * Send back runId
 */
$response['runId'] = $runId; 

echo json_encode($response);
?>