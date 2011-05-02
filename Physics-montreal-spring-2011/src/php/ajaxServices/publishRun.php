<?php 
@session_start();

require_once '../RooloClient.php';
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
$runs = $roolo->search('type:RunConfig AND runid:'.$runId, 'metadata', 'latest');

if (count($runs) != 0){
	$run= $runs[0];
	
	$run->runpublished = 1;
	
	var_dump($run);
	
	$updateResults = $roolo->updateElo($run);
	
	echo "updated elo. result: $updateResults";
}else{
	echo "NO RunConfig found with runId: $runId";
}
