<?php 
require_once '../RooloClient.php';
require_once '../dataModels/Solution.php';
require_once '../Application.php';

error_reporting(E_ALL | E_STRICT);

$rooloClient = new RooloClient();
$curentSolution = new Solution();


$questionUri = '';
/*
 * get questionUri param
 */
if (isset($_REQUEST['questionUri'])){
	$questionUri = $_REQUEST['questionUri'];
}else{
	echo "ERROR: questionUri param not provided";
	die();
}

/*
 * Get the TeacherProgress object
 */
$results = $rooloClient->search('type:TeacherProgress', 'metadata', 'latest');
if (!isset($results[0])){
	echo "ERROR: TeacherProgress object doesn't exist in DB";
	die();
}
$teacherProgress = $results[0];

$progressStr = $teacherProgress->get_progress();
if (strlen($progressStr) == 0){
	$separator = '';
}else{
	$separator = ',';
}
$progressStr .= $separator.$questionUri;
$teacherProgress->set_progress($progressStr);
$rooloClient->updateElo($teacherProgress);

?>