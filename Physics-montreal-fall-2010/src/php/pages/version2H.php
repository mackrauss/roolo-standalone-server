<?php
/*
 *Version #2 (dual mode – starts as homework and continues with in-class group work) (Very similar to Version #1)
 *
 *	H)  Report page is generated showing Long Question with:
 *
 *         i) All MC concept questions with element histogram
 *
 *        ii) Correct answers shown
 *
 *      Report can then be emailed to students or shown in class
 */
 
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/LongProblem.php';
require_once '../dataModels/Solution.php';
require_once '../Application.php';

error_reporting(E_STRICT);

/*
 * Decide which Problems to give a report on
 */
// if there's a runId in the request, serve that
if ($_REQUEST['runId']){
	$runId = $_REQUEST['runId'];
}else{
	$runId = $_SESSION['runId'];
}
/*
 * Get mode param (GROUP or SUPERGROUP) for future
 */
if (isset($_SESSION['mode'])){
	$mode = $_SESSION['mode'];
}else {
	$mode = 'group';
}


$rooloClient = new RooloClient();

$problemObject = new Problem();
$curSolutionObject = new Solution();

/*
 * Extract the LongProblem with specific runId
 */
$longProblemObject = $rooloClient->search('type:LongProblem AND runid:' . $runId, 'metadata', 'latest');
$longProblemPath = $longProblemObject[0]->path;

//echo "long problem path = " . $longProblemPath;

/*
 * Extract all Problems with specific runId for correct answers
 */
$allProblems = $rooloClient->search('type:Problem AND runid:' . $runId, 'metadata', 'latest');

$problemsPathArray = array();
$selectedChoiceArrey = array();
$problemsURI = array();
$categoriesArray = array();

$catCounterJson ='';

$catCounterJsonArray = array();


for($i=0; $i<sizeof($allProblems); $i++){
	$problemObject = $allProblems[$i];
	$problemsPathArray[$i] = $problemObject->path;
	$selectedChoiceArrey[$i] = $problemObject->mcmastersolution;
	
	$solutionOwnerUri = $problemObject->uri;

	// An array for storing all solutions categories of specific problem Object
	$allCatCounter = array();
	foreach (Application::$problemCategories as $curCat){
		$allCatCounter[$curCat] = 0;
	}
	
	// Add dropDown items to the categories array;
	foreach (Application::$dropdownsItems as $items){
	    for($idx = 1; $idx < sizeof($items);$idx++){
	    	$allCatCounter[$items[$idx]] = 0;
        }
	}
		
	// Extract all solutions with specific runId for elements histogram
	$solutionsQuery = 'type:Solution AND runid:' . $runId . ' AND owneruri:' . $rooloClient->escapeSearchTerm($solutionOwnerUri);
	$solutionsArray = $rooloClient->search($solutionsQuery, 'metadata', 'latest');

	for($k=0; $k< sizeof($solutionsArray); $k++){
		
		$curSolutionObject = $solutionsArray[$k];
		$author = strtolower(trim($curSolutionObject->get_author()));
		
		if ( strstr($author, $mode) ){
			// An array for storing solutions categories of specific problem Object
			$catCounter = array();
			foreach (Application::$problemCategories as $curCat){
				$catCounter[$curCat] = 0;
			}

			// Add dropDown items to the categories array;
			foreach (Application::$dropdownsItems as $items){
			    for($idx = 1; $idx < sizeof($items);$idx++){
			    	$catCounter[$items[$idx]] = 0;
		        }
			}

			if (trim($curSolutionObject->category) != ''){
//				echo "</br>categiry = " . trim($curSolutionObject->category). "</br>";
				$curCats = explode(",", trim($curSolutionObject->category));
				foreach ($curCats as $curCat){
//					echo $curCat ."_ ";
					$curCat = trim($curCat);
					$catCounter[$curCat] += 1;	
				}
			}

			//add all categories of specific problem
			foreach ($catCounter as $key => $value){
				$allCatCounter[$key] = $allCatCounter[$key] + $value ;
			}
		}

//		foreach ($allCatCounter as $key => $value) {
//    		echo "Key: $key; Value: $value<br />\n";
//		}
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


	<?php 
	
		for ($i=0; $i<= sizeof ($catCounterJsonArray); $i++) {
	?>
			catCounterArray[<?= $i ?>] = eval (<?= $catCounterJsonArray[$i] ?>);
	<?php 
		}
	?>

  	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['columnchart']});
  
  	// Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);
  
	// Callback that creates and populates a data table, 
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart() {
		/////////////////////////////////////// create data table 
//		var data = new google.visualization.DataTable();
//		// Declare columns and rows.
//		data.addColumn('string', 'Category');  // Column 0 is type string and has label "category".
//		data.addColumn('number', '# of Times Selected'); // Column 1 is type number and has label "Times selected".
			
		for(counter =0; counter < "<?= sizeof($allProblems); ?>"; counter++){

			var data = new google.visualization.DataTable();
			// Declare columns and rows.
			data.addColumn('string', 'Category');  // Column 0 is type string and has label "category".
			data.addColumn('number', '# of Times Selected'); // Column 1 is type number and has label "Times selected".

			for (var key in catCounterArray[counter]){
				data.addRow([key, catCounterArray[counter][key]]);
			}
			// Instantiate and draw chart, passing in some options.
			var chart = new google.visualization.ColumnChart(document.getElementById('categoryReport' + counter));
			chart.draw(data, {
				width: 820, 
				height: 300, 
				is3D: true, 
				title: 'Category Report'});
		}		
	}

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
    	<img height="300px" class="problem" src="<?= $longProblemPath ?>" />
	</div>							 
  	<dd>
  		<?php 
			for($i = 0; $i < sizeof($allProblems); $i++) {
		?>
			<div class='conceptQuestionDiv' id='q<?= $i + 1 ?>'>
				<p class='conceptQuestionLabel'>
					<b> Concept Question <?= $i + 1 ?> </b> (Answer: <?= $selectedChoiceArrey[$i] ?>)
				</p>
			    <div class="questionSection">
			    	<img height="300px" src="<?= $problemsPathArray[$i] ?>" class="problem"/>
			  	</div>
				<div class="answerSection">
					<div id='categoryReport<?= $i ?>'>
					</div>	
				</div>
			</div>							 
		<?php 
			}
		?>
  	</dd>
</div> <!-- id="middle-center"> -->	
<div id="middle-bottom"></div>
 
<label><input type='hidden' id='counter' name='counter' value=""/>

<?php 

require_once './footer.php';
?>