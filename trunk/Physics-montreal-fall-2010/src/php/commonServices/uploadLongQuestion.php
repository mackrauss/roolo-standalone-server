<?php
session_start();

require_once '../RooloClient.php';
require_once '../dataModels/LongProblem.php';

$runId = $_REQUEST['runId'];
$redirectTo = $_REQUEST['redirectTo'];

$appPath = dirname(__FILE__).'/../../..';

$uploadedFileName = $_FILES['longQuestionFile']['name'];
$uploadedFileExtension = substr($uploadedFileName, strrpos($uploadedFileName, '.') + 1);

$roolo = new RooloClient();

/*
 * Find the current number of questions on disk
 */
$target_dir = $appPath.'/problems/'.$runId.'/lq';
$pattern="(\.jpg$)|(\.png$)|(\.jpeg$)|(\.gif$)";
$files = array(); 
$numImages=0;

if($handle = opendir($target_dir)) { 
	while(false !== ($file = readdir($handle))){ 
		if(eregi($pattern, $file)){  
			$numImages++; 
		}
	}
} 

$newFileName = $numImages+1;

/*
 * Upload the problem
 */
$target_path = $target_dir.'/'.$newFileName.'.'.$uploadedFileExtension;

if(move_uploaded_file($_FILES['longQuestionFile']['tmp_name'], $target_path)) {
	
} else{
    echo "There was an error uploading the file, please try again!";
}

/*
 * Create ELO for Long problem
 */
$problem = new LongProblem();
$problem->set_author($_SESSION['username']);
$problem->set_runId($runId);
$problem->path = '/problems/'.$runId.'/lq/'.$newFileName.'.'.$uploadedFileExtension;
$roolo->addElo($problem);

?>

<script type="text/javascript">
window.location.href = '<?= $redirectTo?>';
</script>