<?php
//if (!$_SESSION['loggedIn']){
//	header("Location:/src/php/pages/");
//}

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/RunConfig.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';
require_once '../dataModels/Solution.php';

error_reporting(E_ALL | E_STRICT);

$username = $_SESSION['username'];

$rooloClient = new RooloClient();

$runId = $_REQUEST['runId'];

/*
 * Load run data using the runId
 */
$query = 'type:RunConfig AND runid:'.$runId;
$results = $rooloClient->search($query, 'metadata', 'latest');

$runConfig = $results[0];
/*
 * If the run is already published, return to the run list
 */
if ($runConfig->runPublished == 1){
	header('Location:/src/php/pages/runAuthoring.php');
	die();
}

/*
 * Fetch the MC Problems for this runId
 */
$query = "type:Problem AND runid:".$runId;
$mcProblems = $rooloClient->search($query, 'metadata', 'latest');

/*
 * Fetch the LongProblems for this runId
 */
$query = "type:LongProblem AND runid:".$runId;
$longProblems = $rooloClient->search($query, 'metadata', 'latest');

?>

<script type="text/javascript">
function deleteQuestion(uri){
	$.get('/src/php/commonServices/deleteQuestion.php', {'uri': uri}, function(){
		window.location.reload();
	});
}

</script>
<h2>Edit Run</h2>

<table>
	<tr>
		<td>Run Version</td>
		<td>
			<?= $runConfig->runVersion ?>
		</td>
	</tr>
	<tr>
		<td>Run Class</td>
		<td>
			<?= $runConfig->runClass?>
		</td>
	</tr>
	<tr>
		<td>Available choices up to</td>
		<td>
			<?= $runConfig->runChoiceLimit?>
		</td>
	</tr>
	<tr>
		<td>Run ID</td>
		<td>
			<?= $runConfig->runId?>
		</td>
	</tr>
</table>

<div style='width: 100%; float: left;' id='mcQuestionContainer'>
	<h3>Multiple Choice Questions</h3>
	<div style='float: left; width: 100%; text-align: left;'>
	
<?php 
	foreach ($mcProblems as $curProblem){
		$curUri = $curProblem->uri;
		$curImagePath = $curProblem->path;
		$curSolution = $curProblem->mcmastersolution;
?>
	<div style='float: left; margin-left: 20px;'>
		<a href="<?= $curImagePath?>" target="_blank">
			<img width="100px" height="100px" src="<?= $curImagePath?>" problemUri="<?= $curUri?>" />
		</a>
		<br/>
		Answer: <?= $curSolution ?>
		<br/>
		<a onclick="deleteQuestion('<?= $curUri?>')" style='cursor:pointer;'>delete</a>
	</div>	
<?php 
	}
?>
	</div>	
	<fieldset style='width: 100%; float: left;'>
		<legend>Add Question</legend>
		<form action='/src/php/commonServices/uploadMcQuestion.php' enctype="multipart/form-data" method="post">
			Question: <input type="file" name="mcQuestionFile" id="mcQuestionFile" /> <br/>
			Correct Answer: 
			<select name="mcQuestionAnswer" id='mcQuestionAnswer'>
<?php
	 $runChoiceLimit = $runConfig->runchoicelimit;
	 foreach (Application::$letters as $letter){
	 	echo "<option value='$letter'>$letter</option>";
	 	if ($letter == $runChoiceLimit){
	 		break;
	 	}
	 }
?>
			</select> 
			
			<!-- <input type='text' size='1' name="mcQuestionAnswer" id='mcQuestionAnswer' />  -->
			<br/> 
			
			<input type='submit' value='Add' />
			<input type='hidden' name="redirectTo" id="redirectTo" value="/src/php/pages/runEdit.php?runId=<?= $runConfig->runId ?>" />
			<input type='hidden' name='runId' id='runId' value='<?= $runConfig->runId ?>' />
		</form>
	</fieldset>
</div>
<div style='width: 100%; float: left;' id='lqQuestionContainer'>
	<h3>Long Questions</h3>
<?php 
	foreach ($longProblems as $curProblem){
		$curUri = $curProblem->uri;
		$curImagePath = $curProblem->path;
?>
	<div style='float: left; margin-left: 20px;'>
		<a href="<?= $curImagePath?>" target="_blank">
			<img width="100px" height="100px" src="<?= $curImagePath?>" problemUri="<?= $curUri?>" />
		</a>
		<br/> 
		<a onclick="deleteQuestion('<?= $curUri?>')" style='cursor:pointer;'>delete</a>
	</div>	
<?php 
	}
?>
	<fieldset style='width: 100%; float: left;'>
		<legend>Add Question</legend>
		<form action='/src/php/commonServices/uploadLongQuestion.php' enctype="multipart/form-data" method="post">
			<input type="file" name="longQuestionFile" id="longQuestionFile" />
			
			<input type='submit' value='Add' />
			<input type='hidden' name="redirectTo" id="redirectTo" value="/src/php/pages/runEdit.php?runId=<?= $runConfig->runId ?>" />
			<input type='hidden' name='runId' id='runId' value='<?= $runConfig->runId ?>' />
		</form>
	</fieldset>
	
</div>
