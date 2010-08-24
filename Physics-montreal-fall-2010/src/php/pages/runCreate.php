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

$_SESSION['msg'] = "";
$username = $_SESSION['username'];
?>

<script type="text/javascript">
function createNewRun(){
	//make ajax call to create new run
	var runVersion = $('#runVersion').val();
	var runClass = $('#runClass').val();
	var runChoices = $('#runChoices').val();

	$.getJSON('/src/php/ajaxServices/createNewRun.php', {runVersion: runVersion, runClass:runClass, runChoices: runChoices}, function(data){
		var generatedRunId = data.runId;

		window.location.href = '/src/php/pages/runEdit.php?runId='+generatedRunId;
	});
}
</script>

<h2>Create New Run</h2>

<table>
	<tr>
		<td>Run Version</td>
		<td>
			<select id='runVersion'>
				<option value='1'>1</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Run Class</td>
		<td>
			<select id='runClass'>
				<option value='1'>1</option>
				<option value='2'>2</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Available choices up to</td>
		<td>
			<select id='runChoices'>
				<option value='A'>A</option>
				<option value='B'>B</option>
				<option value='C'>C</option>
				<option value='D'>D</option>
				<option value='E'>E</option>
				<option value='F'>F</option>
				<option value='G'>G</option>
				<option value='H'>H</option>
			</select>
		</td>
	</tr>
</table>

<input type='button' value='Create Run' onclick='createNewRun()' /> 
<?php 
require_once './footer.php';
?>
