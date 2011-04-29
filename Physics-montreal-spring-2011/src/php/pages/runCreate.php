<?php
require_once './adminHeader.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/RunConfig.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';
require_once '../dataModels/Solution.php';

error_reporting(E_ALL | E_STRICT);

$_SESSION['msg'] = "";
$username = $_SESSION['username'];
?>

<script type="text/javascript">
function createNewRun(){
	//make ajax call to create new run
	var runName = $('#runName').val();
	var runVersion = $('#runVersion').val();
	var runClass = $('#runClass').val();
	var runChoices = $('#runChoices').val();

	if (runName.trim() == ''){
		alert('Please give this run a name');
		return;
	}

	$.getJSON('/src/php/ajaxServices/createNewRun.php', {runName: runName, runVersion: runVersion, runClass:runClass, runChoices: runChoices}, function(data){
		var generatedRunId = data.runId;
		if (generatedRunId != null){
			window.location.href = '/src/php/pages/runEdit.php?runId='+generatedRunId;
		}else{
			alert(data.error);
		}
	});
}
</script>

<div id='contentFrame'>
	<div style='float: left; width: 100%; font-size: 14px; margin-bottom: 10px;'>
		<a href='/src/php/pages/runAuthoring.php'>&lt; Back to main page</a>
	</div>
	
	<h2>Create New Run</h2>
	
	<table>
		<tr>
			<td>Run Name</td>
			<td>
				<input type="text" id='runName' />
			</td>
		</tr>
		<input type='hidden' id='runVersion' value='1' />
		<!--
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
		-->
		<tr>
			<td>Run Class</td>
			<td>
				<select id='runClass'>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
					<option value='6'>6</option>
					<option value='7'>7</option>
					<option value='8'>8</option>
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
</div>
 
<?php 
require_once './footer.php';
?>
