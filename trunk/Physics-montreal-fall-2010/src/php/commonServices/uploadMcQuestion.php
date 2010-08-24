<?php
session_start();

$runId = $_REQUEST['runId'];
$appPath = dirname(__FILE__).'/../../..';

$uploadedFileName = $_FILES['mcQuestionFile']['name'];
$uploadedFileExtension = substr($uploadedFileName, strrpos($uploadedFileName, '.') + 1);


/*
 * Find the current number of questions on disk
 */
$target_dir = $appPath.'/problems/'.$runId.'/mc';
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

$target_path = $target_dir.'/'.$newFileName.'.'.$uploadedFileExtension;

if(move_uploaded_file($_FILES['mcQuestionFile']['tmp_name'], $target_path)) {
    echo "The file ".  basename( $_FILES['mcQuestionFile']['name']). 
    " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}
?>