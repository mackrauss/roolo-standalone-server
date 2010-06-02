<?php
require_once './header.php';
error_reporting(E_STRICT | E_ALL);


if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
}


require_once '../RooloClient.php';
require_once '../Application.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';


$username = $_SESSION['username'];
$roolo = new RooloClient();

$noMoreProblemMsg = 'You have finished answering all the questions. You will be logged out in 10 seconds.';

/*
 * check if this user has done a LongAnswer already
 */
$query = "type:LongAnswer AND author:".$username;
$existingLongAnswers = $roolo->search($query, 'metadata', 'latest');

?>


<script type="text/javascript">

	function submitLongAnswer(){
		// get the multiple choice answer
//		var selectedMultipleChoice = $('[name=multipleChoice]:checked');
//		if (selectedMultipleChoice.length == 0){
//			alert('You must choose an answer for question 1');
//			return;
//		}
//		var multipleChoice = selectedMultipleChoice.val();
	
		// get the categories chosen
		var selectedCategories = $('[name=problemCategory]:checked');
		if (selectedCategories.length == 0){
			alert('You must choose at least one principle in question 2');
			return;
		}
		
		var categories = new Array();
		selectedCategories.each(function(i,selected){
			categories[i] = $(selected).val();
		});
		categories = categories.join(', ');
	
		// get the formulas chosen
		var selectedFormulas = $('[name=formula]:checked');
		if (selectedFormulas.length == 0){
			alert('You must choose at least one formula in question 3');
			return;
		}
	
		var formulas = new Array();
		selectedFormulas.each(function(i,selected){
			formulas[i] = $(selected).val();
		});
		formulas = formulas.join(', ');
	
		// get the rationale
		var rationale = $('[name=rationale]').val().trim();
		if (rationale.length == 0){
			alert('Please write a rationle in question 4');
			return;
		}else if (rationale.length > 300){
			alert('Please make sure your rationale is not longer than 300 characters');
			return;
		}
	
		// disable submit button
		$('#submitBtn').attr("disabled", true);
		
		// call the Ajax service to record the LongAnswer submitted
		$.post('/src/php/ajaxServices/saveLongAnswer.php', 
			{
				'longProblemUri': longProblemUri,
				'longProblemPath': longProblemPath,
				'conceptProblemUri': conceptProblemUri,
//				'multipleChoice': multipleChoice, 
				'categories': categories,
				'formulas': formulas,
				'rationale': rationale
			}, function(response){
				console.log(response);
			}
		);
	
	
		$('#middle-top').remove();
		$('#middle-center2').remove();
		$('#middle-bottom').remove();
		
		$('#groupingMsgDiv').css({'width' : '70%', 'height' : '18%', 'margin-left':'15%'});
	
		groupingMsg = "<h2 style='width: 100%; float: left; margin-top: 10%'> <?= $noMoreProblemMsg ?> </h2>";
	
		$('#groupingMsgDiv').html(groupingMsg);
		$('#questionHeading').html('');
		$('#signOut').html('');
		delay();
	}
	
	function delay (){
		setTimeout ("loginPage()", 10000);
	}
	
	function loginPage(){
		window.location = "/src/php/ajaxServices/logout.php";
	} 
	
	function rationaleChanged(){
		$('#numRationaleCharsLeft').html(300 - $('[name=rationale]').val().length);
	}

</script>








<?php

/*
 * If user has not answered a LongAnswer before, 
 */
if (count($existingLongAnswers) == 0){
	/*
	 * Determine which long Problem they should answer and get it from roolo
	 */
	$longProblemIID = Application::$groupLongQuestions[$username];
//	echo $longProblemIID;
//	$longProblemURL = "roolo://scy.collide.info/scy-collide-server/$longProblemIID.Problem";
	
	$query = "type:Problem AND uniquequestionid:".$longProblemIID;
	$longProblemSearchResults = $roolo->search($query, 'metadata', 'latest');
	
	$longProblem = null;
	if (count($longProblemSearchResults) != 1){
		echo "ERROR: Couldn't find the longAnswer with the given URL: ".$longProblemURL;
		die();
	}
	
	$longProblem = $longProblemSearchResults[0];
	
	/*
	 * Get the selected concept problem from the request parameters
	 */
	$conceptProblemUri = $_REQUEST['selectedConceptProblem'];
	$query = "uri:".$roolo->escapeUri($conceptProblemUri);
	$results = $roolo->search($query, 'metadata', 'latest');
	$selectedConceptProblem = $results[0];
	
	
	/*
	 * Get all formulas, their IDs, names and pics
	 */
	$formulas = Application::$forumulas;
	
	/*
	 * Get all Problem Categories 
	 */
	$problemCategories = Application::$problemCategories;

	/*
	 * Extract category count
	 */
	// Find all the solutions created by groups only
	$solutions = $roolo->search('type:Solution AND author:(physicsGroup111 OR physicsGroup211 OR physicsGroup311 OR physicsGroup411) AND owneruri:'.$roolo->escapeSearchTerm($conceptProblemUri), 'metadata', 'latest');
	$catCounter = array();
	foreach (Application::$problemCategories as $curCat){
		$catCounter[$curCat] = 0;
	}
	foreach ($solutions as $curSolution){
		if (trim($curSolution->category) != ''){
			//$curCats = explode(",", trim(mb_strtolower($curSolution->category)));
			$curCats = explode(",", trim($curSolution->category));
			foreach ($curCats as $curCat){
				$curCat = trim($curCat);
				
				$catCounter[$curCat] += 1;	
			}
		}
	}
	
	$catCounterJson = json_encode($catCounter);
	
	
	/*
	 * render view
	 */
?>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	conceptProblemUri = "<?= $conceptProblemUri ?>";
	longProblemUri = "<?= $longProblem->uri?>";
	longProblemPath = "<?= $longProblem->path?>";
catCounter = eval('('+"<?= addslashes($catCounterJson) ?>"+')');

//Load the Visualization API and the piechart package.
google.load('visualization', '1', {'packages':['columnchart']});

	// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

//Callback that creates and populates a data table, 
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {
	// Create our data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Category');
	data.addColumn('number', '# of Times Selected');
	
	for (var key in catCounter){
	data.addRow([key, catCounter[key]]);
	}
	
	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.ColumnChart(document.getElementById('elementsReport'));
	chart.draw(data, {
		width: 820, 
		height: 300, 
		is3D: true, 
		title: 'Elements Report'});
}

</script>

<style type="text/css">
	.formulaLabel {
		color:#1E7EC8;
		font-size:small;
		font-weight:bold;
		line-height:24px;
	}
</style>
<div id="container2">
<div id="middle-top">
	<div id="questionHeading">
	  <p><b>Last Question</b></p>
    </div>
</div>
<div id="middle-center2">
    <div id="questionSection">
    	<img src="<?= $longProblem->path ?>" width="454" height="320" class="problem" />
  	</div>
	  	
  	<div id="conceptQuestion2">
   		<p>Selected Concept Problem</p>
	    <img src="<?= $selectedConceptProblem->path ?>" width="250" height="190"/><br/>
    </div>
    <div style='float: left; width: 100%; padding-left: 50px; padding-top: 40px;'>
    	Review the Elements Report for the selected Concept Problem and then answer the questions below.
    </div>
	<div id='elementsReport'>
	</div>
    <div id="answerSection4">
	  	<form id="round1" name="form1" method="post" action="feedback.php">
<!--	  	<dt>1. Select the correct answer to the LONG problem:</dt>-->
<!--	    <dd>-->
<!--	    	<label class="radioButton"><input type="radio" name="multipleChoice" value="A"/>A</label>-->
<!--	 		<label class="radioButton"><input type="radio" name="multipleChoice" value="B"/>B</label>-->
<!--	  		<label class="radioButton"><input type="radio" name="multipleChoice" value="C"/>C</label>-->
<!--	  		<label class="radioButton"><input type="radio" name="multipleChoice" value="D"/>D</label>-->
<!--	  		<label class="radioButton"><input type="radio" name="multipleChoice" value="E"/>E</label>-->
<!--	  		<label class="radioButton"><input type="radio" name="multipleChoice" value="F"/>F</label>-->
<!--	  		<label class="radioButton"><input type="radio" name="multipleChoice" value="G"/>G</label>-->
<!--	  		<label class="radioButton"><input type="radio" name="multipleChoice" value="H"/>H</label>-->
<!--	  	</dd>-->
	 	<dt>1. Check the corresponding elements that are shown in the LONG problem:</dt>
	  	<dd>
<?php 
	foreach ($problemCategories as $curProblemCategory) {
?>
	  		<label><input type="checkbox" name="problemCategory" value="<?= $curProblemCategory?>" /><?= $curProblemCategory?></label><br/>
<?php 
	}
?>
	  	</dd>
	    <dt>2. Check off the corresponding formulas that you would use to solve the LONG problem.</dt>
	    <dd>
<?php 
	foreach ($formulas as $curFormulaName => $curFormula){
?>
	    	<label>
	    		<table>
	    			<tr>
	    				<td> <input type="checkbox" name="formula" value="<?= $curFormula['id']?>" /> </td>
	    				<td class='formulaLabel'> <?= $curFormulaName?> </td> 
	    				<td> <img src="<?= $curFormula['path'] ?>" /> </td>
	    			</tr>
	    		</table> 
	    	</label>
	    	<br/>
<?php 
	}
?>
	    </dd>
	    <dt>3. Tell us why you chose the formulas you chose and how you would use those formulas to solve the problem.</dt>
	  	<dd><textarea name="rationale" cols="60" rows="12" onKeyUp='rationaleChanged();'></textarea></dd>
	
	  	<p><span id='numRationaleCharsLeft'>300</span> characters left</p>
	  	<input id='submitBtn' name="submit" type="button" value="SUBMIT" class="btn" onclick="submitLongAnswer();"/>
	  	</form>
	<!-- end #answerSection -->
	</div>
</div>
<div id="middle-bottom">
</div>

<?php
} 
/*
 * If user has already answered  a LongAnswer,
 */
else {
?>
<h2 style="width: 60%; height: 18%; margin-left: 20%"> You've already answered your last question. <br/><br/> If you believe this is an error, please let your teacher know! Otherwise, please log out. </h2> 
<script type="text/javascript"> 
	delay();
</script>
<?php
}
?>

<div id="groupingMsgDiv"> </div>

<?php 
require_once './footer.php';
?>