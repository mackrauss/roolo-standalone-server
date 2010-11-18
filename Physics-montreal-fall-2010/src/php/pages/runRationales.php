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

$username = $_SESSION['username'];

$rooloClient = new RooloClient();

$runId = $_REQUEST['runId'];
$mode = isset($_REQUEST['problemUri']) ? 'LOAD_PROBLEM' : 'FIRST_TIME';  



//Retrive related RunConfig elo for runchoicelimit element
$runConfigObj = new RunConfig();
$runConfigQuery = "type:RunConfig AND runid:" . $runId;
$runConfigArray = $rooloClient->search($runConfigQuery, 'metadata');
$runConfigObj = $runConfigArray[0];
$runChoiceLimit = $runConfigObj->runchoicelimit;
$choicesArray = explode( ", "  , $runChoiceLimit );

//Retrieve all problems object for specific runID
$problemQuery = "type:Problem AND runid:" . $runId ;
$allProblems = $rooloClient->search($problemQuery, 'metadata', 'latest');


if ($mode == 'FIRST_TIME'){
	$firstProblem = $allProblems[0];
	$problemUri = $firstProblem->uri;
}else{
	$problemUri = $_REQUEST['problemUri'];
}

//Retrieve the active problem from DB
$problem = $rooloClient->search("uri:".$rooloClient->escapeSearchTerm($problemUri), 'metadata', 'latest');
$problem = $problem[0];





/**
 * ****************************** PREPRE FOR LOADING PAGE ******************************** /
 */
//Determine all exist solutions for this problem
$solutionQuery = "type:Solution AND owneruri:". $rooloClient->escapeSearchTerm($problemUri) ." AND runid:" . $runId;
$solutions = $rooloClient->search($solutionQuery, 'metadata');

$mcCounter = array();
// input all default choices (A,B,...) & set value to 0
foreach ($choicesArray as $curChoice){
	$mcCounter[$curChoice] = 0;
}
foreach ($solutions as $curSolution){
	$mcCounter[trim($curSolution->selectedchoice)] += 1;
}

$mcCounterJson = json_encode($mcCounter);

?>












<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
mcCounter  = <?= $mcCounterJson ?>;
catChart = null;
mcChart = null;

// Load the Visualization API and the piechart package.
google.load('visualization', '1', {'packages':['columnchart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);
	
// Callback that creates and populates a data table, 
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {
//////////////////////////////////////// MULTIPLE CHOICE ///////////////////////////////////////////////
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Multiple Choice');
	data.addColumn('number', '# of Times Selected');
	
	for (var key in mcCounter){
		data.addRow([key, mcCounter[key]]);
	}
	// Instantiate and draw our chart, passing in some options.
	mcChart = new google.visualization.ColumnChart(document.getElementById('mcReport'));
	mcChart.draw(data, {
		width: 350, 
		height: 300, 
		is3D: true, 
		title: 'Multiple Choice Report'});
	
	google.visualization.events.addListener(mcChart, 'select', function(event){
		var selectionObject = mcChart.getSelection();
		var selectedIndex = selectionObject[0].row;
		var curIndex = 0;
		var selectedMc = '';
		var questionUri = '<?= $problem->uri ?>';
		
		for (var mcChoice in mcCounter){
			if (curIndex == selectedIndex){
				selectedMc = mcChoice;
				break;
			}
			curIndex++;
		}
		$.get(	"/src/php/ajaxServices/getCategoryCount.php",
		{
			problemUri:questionUri,
			mcChoice:selectedMc,
			runId:"<?= $runId ?>",
			mode:"<?= $mode ?>"
		},
  		function(data){
	  		data = eval('('+data+')');
	  		
	  		var rationales = data.rationales;
	  		var catCounter = data.catCounter;
	  		 
			recreateCatChart(catCounter);
			renderRationales(rationales);
		});
	});

  
}

function renderRationales(rationales){
var mainDiv = $('#rationalesReport');

//clear the report div
mainDiv.html('');

//header
var headerDiv = "<div id='rationalesReportHeader'>Other teams' rationales for choosing this answer</div>";
mainDiv.append(headerDiv);

for (var i=0;i<rationales.length;i++){
	curRationale = rationales[i];
	rationaleDiv = 	"<div style='float: left; width: 80%; margin-bottom: 5px;'>"+ 
					curRationale.text +
					"<span style='margin-left: 5px; font-size: 10;'>"+
					curRationale.author +
					"</span></div>";

	mainDiv.append(rationaleDiv);
}
}

function recreateCatChart(catCounter){
// Create our data table.
var data = new google.visualization.DataTable();
data.addColumn('string', 'Category');
data.addColumn('number', '# of Times Selected');

for (var key in catCounter){
	data.addRow([key, catCounter[key]]);
}

// Instantiate and draw our chart, passing in some options.
catChart = new google.visualization.ColumnChart(document.getElementById('elementsReport'));
catChart.draw(data, {
	width: 820, 
	height: 300, 
	is3D: true, 
	title: 'Elements Report'}
);
}
	function selectProblem(){
		var runId = '<?= $runId ?>';
		var problemUri = $('#problemSelector :selected').val();

		window.location.href = '?runId='+runId+'&problemUri='+problemUri;
	}
</script>



<style tyle='text/css'>
	div#questionHeading {
		height: 50px;
		width: 300px;
		margin:0;
		padding-top:30px;
		padding-left:40px;
		font-family:Arial, Helvetica, sans-serif;
		font-size:x-large;
		color:#1E7EC8;
	}
	div#middle-center {
		margin: 0 auto;
		height:2200px;
	}
	div#mcReport {
		top:20px;
	}
</style>


<div style='float: left; width: 100%; margin-top: 10px; margin-bottom: 20px; '>
	<a href='/src/php/pages/runAuthoring.php'> &lt; Back to main page</a>
</div>
<div id="middle-center" >
	<select name='problemSelector' id='problemSelector' onchange='selectProblem();'>
	<?php 
		foreach ($allProblems as $curProblem){
			$curProblemUri = $curProblem->uri;
			$curProblemPath = $curProblem->path;
	
			if ($curProblemUri == $problemUri){
				$selected = 'selected';
			}else{
				$selected = '';
			}
			echo "<option $selected value='$curProblemUri'>$curProblemPath</option>";
		}
	?>
	</select>



    <div id="questionSection">
    	<img id='curQuestion' src="<?= $problem->path ?>" width="454" height="320" class="problem"/>
  	</div>
  	
	<div id='mcReport' >
	</div>
	<div id='elementsReport'>
		Please click on a Multiple Choice bar first
	</div>
	<div id='rationalesReport'>
		
	</div>
</div>