<?php
require_once './adminHeader.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';
require_once '../dataModels/Solution.php';

error_reporting(E_ALL | E_STRICT);

$_SESSION['msg'] = "";
$username = @$_SESSION['username'];

$rooloClient = new RooloClient();

/*
 * Get all runs in the system
 */
$query = "type:RunConfig";
$runConfigs = $rooloClient->search($query, 'metadata', 'latest');

?>

<style type="text/css">
/*                     funky table                 **/
#currentRunsTable {
	width: 800px;
	padding: 0;
	margin: 0;
	margin-left: 300px;
	margin-bottom: 20px;
}

caption {
	padding: 0 0 5px 0;
	width: 700px;	 
	font: italic 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	text-align: right;
}

th {
	font: bold 14px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	color: #4f6b72;
	border-right: 1px solid #C1DAD7;
	border-bottom: 1px solid #C1DAD7;
	border-top: 1px solid #C1DAD7;
	letter-spacing: 2px;
	text-transform: uppercase;
	text-align: left;
	padding: 6px 6px 6px 12px;
	background: #CAE8EA url(/resources/images/bg_header.jpg) no-repeat;
}

th.nobg {
	border-top: 0;
	border-left: 0;
	border-right: 0;
	background: none;
}

td {
	border-right: 1px solid #C1DAD7;
	border-bottom: 1px solid #C1DAD7;
	background: #fff;
	padding: 6px 6px 6px 12px;
	color: #4f6b72;
	font-family: "Trebuchet MS",Verdana,Arial,Helvetica,sans-serif;
	font-size: 12px;
}


td.alt {
	background: #F5FAFA;
	color: #797268;
}

th.spec {
	border-left: 1px solid #C1DAD7;
	border-top: 0;
	background: #fff url(/resources/images/bullet1.gif) no-repeat;
	font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
}

th.specalt {
	border-left: 1px solid #C1DAD7;
	border-top: 0;
	background: #f5fafa url(/resources/images/bullet2.gif) no-repeat;
	font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	color: #797268;
}
/*                     funky table [end]                 **/

a {
	cursor: pointer;
	text-decoration: underline;
	color: #4F6B72;
}


/* rounded buttons */
.round {
	cursor:pointer; 
	cursor:hand; 
	line-height:27px;
	height:27px;
	background:url(/resources/images/button.gif) no-repeat right top; 
	padding-right:30px; 
	display:inline-block;
	color: #534010;
	text-decoration: none;
	/*font-weight: bold;*/
}

.round ins { 
	background:url(/resources/images/button.gif) no-repeat left top; 
	height:27px;
	line-height:27px;
	display:inline-block;
	padding-left:30px;
}

	a.round:hover {background-position:right -155px;}
	a.round:hover ins {background-position:left -155px;}
	

/* end rounded buttons */
</style>

<script type="text/javascript">
function resetApplication(){
	var resetConfirmed = confirm('Are you sure you want to delete the whole contents of the application?');
	if (resetConfirmed){
		$.get('/src/php/ajaxServices/resetApplication.php', {}, function(data){
			window.location.reload();
		});

		$('input[type=button]').attr("disabled", true);
	}
}

function setupTestEnvironment(){
	$('input[type=button]').attr("disabled", true);
	$.get('/src/php/ajaxServices/setupTestEnvironment.php', {}, function(data){
		window.location.reload();
	});
}

function exportRunData(exportLink){
	var runId = $(exportLink).attr('runId');

//	email = prompt("Please enter an email address that the data package will be sent to");

	$.get('/src/php/ajaxServices/exportRunData.php', {runId: runId}, function(data){
		data = data.trim();
		window.location.href = data;
	});
}

function publishRun(publishLink){
	alert('hello');
	var runId = $(publishLink).attr('runId');

	$.get('/src/php/ajaxServices/publishRun.php', {runId: runId}, function(data){
		if (data == ''){
			//window.location.reload();
		}else{
			console.log(data);
		}
		
	});
}

function deleteRun(deleteLink){
	var runId = $(deleteLink).attr('runId');

	$.get('/src/php/ajaxServices/deleteRun.php', {runId: runId}, function(data){
		window.location.reload();
	});
}
</script>

<div id='mainContainer' style='height: 1000px;'>
<h2>Current Runs</h2>
<table id='currentRunsTable'>
	<tr>
		<th class='nobg'>ID</th>
		<th class='nobg'>NAME</th>
		<!-- <th class='nobg'>Version</th>  -->
		<th class='nobg'>Class</th>
		<th class='nobg'>Created On</th>
		<th class='nobg'>Status</th>
		<th class='nobg' style='width: 300px;'>Actions</th>
	</tr>
<?php
// columns: runId, runVersion, runClass, created on, actions (edit, delete, publish, export data)
foreach ($runConfigs as $curRunConfig){
	$runId = $curRunConfig->runid;
	$runName = $curRunConfig->runname;
	$runVersion = $curRunConfig->runversion;
	$runClass = $curRunConfig->runclass;
	$createdOn = date("F j, Y, g:i a",$curRunConfig->datecreated/1000);//$curRunConfig->datecreated;//date("F j, Y, g:i a", 35631076);// //date("F j, Y, g:i a", );
	$isPublished = $curRunConfig->runpublished == 1;
	$runStatus = $isPublished ? 'Published' : 'Awaiting Publication';
	
	$editAction = "<a href='/src/php/pages/runEdit.php?runId=$runId'>edit</a>";
	$seeAction = "<a href='/src/php/pages/runEdit.php?runId=$runId'>see</a>";
	$publishAction = "<a onclick='publishRun(this);' runId='$runId'>publish</a>";
	$deleteAction = "<a onclick='deleteRun(this)' runId='$runId'>delete</a>";
	$exportAction = "<a onclick='exportRunData(this)' runId='$runId'>export data</a>";
	$seeIndVisAction = "<a href='/src/php/pages/runReport.php?runId=".$runId."&scope=ind'>individual report</a>"; 
	$seeGrpVisAction = "<a href='/src/php/pages/runReport.php?runId=".$runId."&scope=grp'>group report</a>";
	$seeLqReport = "<a href='/src/php/pages/groupReportLongQuestion.php?runId=$runId' target='_blank'>long question report</a>";
	$seeIndRationalesAction = "<a href='/src/php/pages/runRationales.php?runId=".$runId."&scope=ind'>Rationales Report</a>";
	$seeMainVizAction = "<a href='/src/php/pages/runReport.php?runId=$runId'>Main Report</a>";
	
	$actions = "";
	if (!$isPublished){
		$actions .= $editAction .' | '. $publishAction .' | ';
	}else{
		$actions .= $seeAction . ' | ';
	}
	$actions .= "$deleteAction |  $exportAction <br/> 
					<ul>
						<li>$seeMainVizAction</li>
						<li>$seeIndRationalesAction</li>
					</ul>";
	
	echo 
	"<tr>
		<td>$runId</td>
		<td>$runName</td>
		<!-- <td>$runVersion</td>  -->
		<td>$runClass</td>
		<td>$createdOn</td>
		<td>$runStatus</td>
		<td>$actions</td>
	</tr>
	";
}
?>

</table>


<input type='button' onclick="window.location.href='/src/php/pages/runCreate.php';" value='Create New Run' />
<input type='button' onclick="resetApplication();" value='Reset Application' />
<input type='button' onclick="setupTestEnvironment();" value='Setup Test Environment'/>

</div>
<?php 
require_once './footer.php';
?>
