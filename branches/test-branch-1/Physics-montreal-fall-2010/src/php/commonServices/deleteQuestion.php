<?php
session_start();

require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';

$problemUri = $_REQUEST['uri'];

$roolo = new RooloClient();
/*
 * Get problem ELO
 */
$problem = $roolo->search('uri:'.$roolo->escapeSearchTerm($problemUri), 'metadata', 'latest');
$problem = $problem[0];

/*
 * Delete the problem image from disk
 */
$filename = basename($problem->path);
$appPath = dirname(__FILE__).'/../../..';
$problemType = $problem->type;
if ($problemType == 'Problem'){
	$problemTypeDir = 'mc';
}elseif($problemType == 'LongProblem'){
	$problemTypeDir = 'lq';
}
$filepath = $appPath.'/problems/'.$problem->runid.'/'.$problemTypeDir.'/'.$filename;
unlink($filepath);


/*
 * Delete the Problem ELO
 */
$roolo->deleteElo($problemUri);
?>