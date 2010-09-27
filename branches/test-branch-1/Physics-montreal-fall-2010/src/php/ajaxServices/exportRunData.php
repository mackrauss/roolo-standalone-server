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
 * Find all ELOs that are attached to this run
 */
$runElos = $roolo->search('runid: '.$runId, 'metadata', 'latest');

/*
 * Get all the graphml data
 */