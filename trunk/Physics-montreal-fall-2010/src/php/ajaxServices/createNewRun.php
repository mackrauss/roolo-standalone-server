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

/*
 * Create new run configuration ELO
 */
$runConfig = new RunConfig();
$runConfig->runVersion = $runVersion;
$runConfig->runClass = $runClass;
$runConfig->runId = 'v'.$runVersion.'_c'.$runClass;
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
$response = array();
$response['runId'] = $runId; 

echo json_encode($response);
?>