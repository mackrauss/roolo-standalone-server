<?php 
@session_start();

require_once '../RooloClient.php';
require_once '../CommonFunctions.php';
require_once '../dataModels/RunConfig.php';

/*
 * Get Params
 */
$runId = $_REQUEST['runId'];

/*
 * Setup variables
 */
$roolo = new RooloClient();

/*
 * Find the RunConfig with given runId
 */
$runs = $roolo->search('type:RunConfig AND runid: '.$runId, 'metadata', 'latest');

if (count($runs) != 0){
	$run= $runs[0];
	$runUri = $run->uri;
	
	/*
	 * Delete RunConfig ELO
	 */
	$roolo->deleteElo($runUri);
	
	/*
	 * Delete problems directory
	 */
//	`rm -rf ../../../problems/$runId`;
	deleteDirContents("../../../problems/$runId");
	
	/*
	 * Delete graphml directory
	 */
//	`rm -rf ../../../graphml/$runId`;
	deleteDirContents("../../../graphml/$runId");
}