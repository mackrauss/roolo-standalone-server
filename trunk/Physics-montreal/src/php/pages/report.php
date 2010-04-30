<?php
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';

error_reporting(E_ALL | E_STRICT);

$roolo = new RooloClient();

/*
 * Extract all the questions
 */
$questions = $roolo->search('type:Problem', 'metadata', 'latest');

/*
 * Decide which question to give a report on
 */
// if there's a problemUri in the request, serve that
if ($_REQUEST['problemUri']){
	$questionUri = $_REQUEST['problemUri'];
}else{
	$questionUri = $questions[0]->get_uri();
}

/*
 * Find the question we're dealing with right now: 
 */
foreach ($questions as $curQuestion){
	if ($curQuestion->get_uri() == $questionUri){
		$question = $curQuestion;
	}
}

/**
 * Find all the solutions 
 */
$solutions = $roolo->search('type:Solution AND owneruri:'.$roolo->escapeSearchTerm($questionUri), 'metadata', 'latest');



/*
 * Extract multiple choice count
 */
$mcCounter = array();
foreach (Application::$problemChoices as $curChoice){
	$mcCounter[$curChoice] = 0;
}

foreach ($solutions as $curSolution){
	$curMc = trim(mb_strtolower($curSolution->selectedchoice));
	
	if ($curMc != '' and $curMc != 'undefined'){
		$mcCounter[$curMc] += 1;
	}
}

/*
 * Extract category count
 */
$catCounter = array();
foreach (Application::$problemCategories as $curCat){
	$catCounter[$curCat] = 0;
}
foreach ($solutions as $curSolution){
	if (trim($curSolution->category) != ''){
		$curCats = explode(",", trim(mb_strtolower($curSolution->category)));
		foreach ($curCats as $curCat){
			$curCat = trim($curCat);
			
			
			$catCounter[$curCat] += 1;	
		}
	}
}

$mcCounterJson = json_encode($mcCounter);
$catCounterJson = json_encode($catCounter);
?>
<div style='float: left; width: 900; padding-left: 250px;'>
	<div style='float: left; width: 100%;' >
		<form method='GET' action=''>
			Please choose a questions to being: 
			<select name='problemUri' onchange="this.form.submit()">
<?php
foreach ($questions as $curQuestion){
	$selected = ($curQuestion->get_uri() == $questionUri) ? 'selected' : '';
	echo "<option value='".$curQuestion->get_uri()."' $selected>".$curQuestion->path."</option>";
}
?>
			</select>
		</form>
	</div>
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
	
		catCounter = eval(<?= $catCounterJson?>);
		mcCounter  = eval(<?= $mcCounterJson?>);
		console.log(mcCounter);
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
			var chart = new google.visualization.ColumnChart(document.getElementById('mcVis'));
			chart.draw(data, {
				width: 400, 
				height: 330, 
				is3D: true, 
				title: 'Multiple Choice Report'});
			
		  
			//////////////////////////////////////// CATEGOREIES ///////////////////////////////////////////////
		    // Create our data table.
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Category');
			data.addColumn('number', '# of Times Selected');
			
			for (var key in catCounter){
			data.addRow([key, catCounter[key]]);
			}
			
			// Instantiate and draw our chart, passing in some options.
			var chart = new google.visualization.ColumnChart(document.getElementById('catVis'));
			chart.draw(data, {
				width: 875, 
				height: 300, 
				is3D: true, 
				title: 'Category Report'});
		}
	</script>
	
	<div style='float: left; width: 45%;  border: 8px solid #AAAAAA;  margin-bottom: 20px;' id='mcVis'></div>
	<div style='float: right; width: 422px; border: 1px solid black; margin-bottom: 20px; margin-right: 10px;'>
		<img src='<?= $question->path ?>' />
	</div>
	<div style='float: left; width: 875px; border: 8px solid #AAAAAA; margin-bottom: 20px;' id='catVis'></div>
	
</div>	


<?php 
require_once './footer.php';
?>
