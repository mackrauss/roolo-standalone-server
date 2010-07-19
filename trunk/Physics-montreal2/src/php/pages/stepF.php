<?php

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';
require_once '../Application.php';

error_reporting(E_STRICT);

$rooloClient = new RooloClient();

$problemObject = new Problem();
$solutionObject = new Solution();

//Retrieve longQuestion object
$uniqueQuestionIds = Application::$longQuestion;
$query = "type:Problem AND uniquequestionid:(" . $uniqueQuestionIds . ")";
$problemObject = $rooloClient->search($query, 'metadata', 'latest');
$longProblemPath = $problemObject[0]->path;

//Retrieve all short question object
$uniqueQuestionIds = Application::$shortQuestions;
$uniqueQuestionIdStr = implode(' OR ', $uniqueQuestionIds);
$query = "type:Problem AND uniquequestionid:(" . $uniqueQuestionIdStr . ")";
$allProblems = $rooloClient->search($query, 'metadata', 'latest');


$problemsArray = array();
$selectedChoiceArrey = array();
$problemsURI = array();
$categoriesArray = array();

$catCounterJson ='';

$catCounterJsonArray = array();

//$authors = Application::$authors;

for($i=0; $i<sizeof($allProblems); $i++){
	$problemObject = $allProblems[$i];
	$problemsArray[$i] = $problemObject->path;
	$selectedChoiceArrey[$i] = $problemObject->mcmastersolution;

	$solutionOwnerUri = $problemObject->uri;

	// An array for storing all solutions categories of specific problem Object
	$allCatCounter = array();
	foreach (Application::$problemCategories as $curCat){
		$allCatCounter[$curCat] = 0;
	}
	
	//Find all solutions Object for specific problem Object;
	foreach (Application::$authors as $authorSolution){
	
		// Find ll the solutions and return suitable data 
		//require_once '../ajaxServices/retrieveCategories.php';
		
		$solution = $rooloClient->search('type:Solution AND author:'.$authorSolution.' AND owneruri:'.$rooloClient->escapeSearchTerm($solutionOwnerUri), 'metadata', 'latest');
		$curSolution = $solution[0];
		
		// Extract category count
		$catCounter = array();
		foreach (Application::$problemCategories as $curCat){
			$catCounter[$curCat] = 0;
		}

		if (trim($curSolution->category) != ''){
			$curCats = explode(",", trim($curSolution->category));
			foreach ($curCats as $curCat){
				$curCat = trim($curCat);
				
				$catCounter[$curCat] += 1;	
			}
		}

		//add all categories of soecific problem
		foreach (Application::$problemCategories as $curCat){
			$allCatCounter[$curCat] = $allCatCounter[$curCat] + $catCounter[$curCat] ;
		}
	}
	// Make json 
	$catCounterJsonArray[$i] = json_encode($allCatCounter);
}

?>

<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>

<script type="text/javascript">
//$(document).ready(function(){
	var catCounterArray = new Array(4);
		
	catCounterArray[0] = eval(<?= $catCounterJsonArray[0] ?>);
	catCounterArray[1] = eval(<?= $catCounterJsonArray[1] ?>);
	catCounterArray[2] = eval(<?= $catCounterJsonArray[2] ?>);
	catCounterArray[3] = eval(<?= $catCounterJsonArray[3] ?>);

  	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['columnchart']});
  
  	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);
  
	// Callback that creates and populates a data table, 
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart() {

	    ////////////////////////////////////// Create data table for first question.
		var data_1 = new google.visualization.DataTable();
		data_1.addColumn('string', 'Category');
		data_1.addColumn('number', '# of Times Selected');
		
		for (var key in catCounterArray[0]){
			data_1.addRow([key, catCounterArray[0][key]]);
		}
		
		// Instantiate and draw chart, passing in some options.
		var chart_1 = new google.visualization.ColumnChart(document.getElementById('categoryReport0'));
		chart_1.draw(data_1, {
			width: 820, 
			height: 300, 
			is3D: true, 
			title: 'Category Report'});

	    ////////////////////////////////////// Create data table for second question.
		var data_2 = new google.visualization.DataTable();
		data_2.addColumn('string', 'Category');
		data_2.addColumn('number', '# of Times Selected');
		
		for (var key in catCounterArray[1]){
			data_2.addRow([key, catCounterArray[1][key]]);
		}
		
		// Instantiate and draw chart, passing in some options.
		var chart_2 = new google.visualization.ColumnChart(document.getElementById('categoryReport1'));
		chart_2.draw(data_2, {
			width: 820, 
			height: 300, 
			is3D: true, 
			title: 'Category Report'});
		
	    ////////////////////////////////////// Create data table for thired question.
		var data_3 = new google.visualization.DataTable();
		data_3.addColumn('string', 'Category');
		data_3.addColumn('number', '# of Times Selected');
		
		for (var key in catCounterArray[2]){
			data_3.addRow([key, catCounterArray[2][key]]);
		}
		
		// Instantiate and draw chart, passing in some options.
		var chart_3 = new google.visualization.ColumnChart(document.getElementById('categoryReport2'));
		chart_3.draw(data_3, {
			width: 820, 
			height: 300, 
			is3D: true, 
			title: 'Category Report'});
		
	    ////////////////////////////////////// Create data table for fourth question.
		var data_4 = new google.visualization.DataTable();
		data_4.addColumn('string', 'Category');
		data_4.addColumn('number', '# of Times Selected');
		
		for (var key in catCounterArray[3]){
			data_4.addRow([key, catCounterArray[3][key]]);
		}
		
		// Instantiate and draw chart, passing in some options.
		var chart_4 = new google.visualization.ColumnChart(document.getElementById('categoryReport3'));
		chart_4.draw(data_4, {
			width: 820, 
			height: 300, 
			is3D: true, 
			title: 'Category Report'});

	}
//}
</script>

<style tyle='text/css'>
	
	div#answerSection {
		height: 350px;
	}
	
	div#mainQuestion {
		float:left;
		left:40px;
		position:relative;
		top:20px;
		width:100%;
		margin-bottom: 15px;
	}
	label.answer {
		float:left;
		left:40px;
		position:relative;
		top:20px;
		width:300px;
	
	}

	label.tag {
		float:left;
		left:40px;
		position:relative;
		top:20px;
		width:300px;
	
	}
	
	div.conceptQuestionDiv {
		width: 100%;
		float: left;
		margin-top: 20px;
	}
	
	p.conceptQuestionLabel {
		margin-left: 40px;
	}
	
	div.questionSection {
		width: 100%;
		margin-left: 40px;
		float: left;
	}
	
	div.answerSection {
		width: 100%;
		float: left;
		margin-left: 40px;
		margin-top: 30px;
	}
	
	
//	div#questionHeading {
//		height: 50px;
//		width: 300px;
//		margin:0;
//		padding-top:30px;
//		padding-left:40px;
//		font-family:Arial, Helvetica, sans-serif;
//		font-size:x-large;
//		color:#1E7EC8;
//	} 

</style>

<div id="container2">

<div id="middle-top">
	<div id="questionHeading"><p><b></b></p></div>
	
</div>

<div id='groupingMsgDiv'></div>

<div id="middle-center2">

	<div id='mainQuestion'>
    	<img height="500px" class="problem" src="<?= $longProblemPath ?>" />
	</div>							 
	<div class='conceptQuestionDiv' id='q1'>
		<p class='conceptQuestionLabel'>
			<b> Concept Question 1 </b> (Answer: <?= $selectedChoiceArrey[0] ?>)
		</p>
	    <div class="questionSection">
	    	<img height="500px" src="<?= $problemsArray[0] ?>" class="problem"/>
	  	</div>
		<div class="answerSection">
			<div id='categoryReport0'>
			</div>	
		</div>
	</div>							 
	<div class='conceptQuestionDiv' id='q2'>
		<p class='conceptQuestionLabel'>
			<b> Concept Question 2 </b> (Answer: <?= $selectedChoiceArrey[1] ?>)
		</p>
	    <div id="questionSection">
	    	<img height="500px" src="<?= $problemsArray[1] ?>" class="problem"/>
	  	</div>
		<div class="answerSection">
			<div id='categoryReport1'>
			</div>	
		</div>
	</div>							 
	<div class='conceptQuestionDiv' id='q3'>
		<p class='conceptQuestionLabel'>
			<b> Concept Question 3 </b> (Answer: <?= $selectedChoiceArrey[2] ?>)
		</p>
	    <div id="questionSection">
	    	<img height="500px" src="<?= $problemsArray[2] ?>" class="problem"/>
	  	</div>
		<div class="answerSection">
			<div id='categoryReport2'>
			</div>	
		</div>
	</div>							 

</div> <!-- id="middle-center"> -->	
<div id="middle-bottom"></div>
 
<label><input type='hidden' id='counter' name='counter' value=""/>

<?php 

require_once './footer.php';
?>