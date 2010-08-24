<?php
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/RunConfig.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';
require_once '../dataModels/Solution.php';

error_reporting(E_ALL | E_STRICT);

if (!$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
}

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


?>

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
	
	<fieldset style='width: 100%; float: left;'>
		<legend>Add Question</legend>
		<form action='/src/php/commonServices/uploadMcQuestion.php' enctype="multipart/form-data" method="post">
			<input type="file" name="mcQuestionFile" id="mcQuestionFile" />
			<input type='text' size='1' name="mcQuestionAnswer" id='mcQuestionAnswer' />
			
			<input type='submit' value='Add' />
			<input type='hidden' name="redirectTo" id="redirectTo" value="/src/php/pages/runEdit.php?runid=<?= $runConfig->runId ?>" />
			<input type='hidden' name='runId' id='runId' value='<?= $runConfig->runId ?>' />
		</form>
	</fieldset>
</div>
<div style='width: 100%; float: left;' id='lqQuestionContainer'>
	<h3>Long Questions</h3>
	
</div>
<?php 
require_once './footer.php';
?>