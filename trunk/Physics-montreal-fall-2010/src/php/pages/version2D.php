<?php
/*
* Version #2 (dual mode _ starts as homework and continues with in-class group work) (Very similar to Version #1)
* 
* D)Students are preselected into groups of four by the teacher (or self organized). Two pairs sharing 2 computers (the pod).
*
* Steps in activity:
*
*          i) The students log in with their assigned “Student Group login”.
*
* 			*** Note but Not coded -  Students log which supergroup they’re in by signing a sheet that is passed around the classroom.
*
*         ii) Student Group is shown one of the MC questions from the set with the histogram of answers.
* 			  Students can click on an answer to see rationales from students who chose that answer and the tagging histogram for those
* 			  who chose that answer.
*
*		 iii) Student Group then:
*				a.Re-votes the answer
*				b.Re-tags the elements
*				c.Writes a rationale for the elements (not the Answer)
*				d.Click submit
*
*		  iv) Student Group sees splash screen which shows text “Question X is next.  Wait for teacher instructions.”
*				a.Student Pair can then either:
*                    i. Click “Next Question”
*                   ii. Or “Log out”
*
*		   v) If Student Group is instructed to go to the “Next Question” then repeat steps ii-iv
*				a.Else Student Group logs out and Go To Step F
*/
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/SuperSummary.php';
require_once '../dataModels/RunConfig.php';
require_once '../Application.php';
require_once '../dataModels/Solution.php';

error_reporting(E_ALL | E_STRICT);

$_SESSION['msg'] = "";
$username = $_SESSION['username'];
$runId = $_SESSION['runId'];
$greetingMsg = "Signed in as <b> " . $username . "</b>";
$noMoreProblemMsg1 = 'Thanks!';
$noMoreProblemMsg2 = 'You will be logged out in 10 seconds. Please wait for instructions...';

/*
 * Get mode param (GROUP or SUPERGROUP) for future
 */
if (isset($_SESSION['mode'])){
	$mode = $_SESSION['mode'];
}else {
	$mode = 'group';
}

if (!$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
//	$_SESSION['loggedIn'] = true;
//	$_SESSION['username'] = $_REQUEST['username'];
}

$rooloClient = new RooloClient();

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

//Determine all exist solutions for the specific group
$solutionQuery = "type:Solution AND author:" . $username . " AND runid:" . $runId;
$authorSolutions = $rooloClient->search($solutionQuery, 'metadata');

$solutionObject = new Solution();
$problemObject = new Problem();
$problems = Array();

//Retrieve all problems without solution
for($i=0; $i<sizeof($allProblems); $i++){
	$problemObject = $allProblems[$i];
	$uri = $problemObject->uri;
	$found = FALSE;
	for($j=0; $j<sizeof($authorSolutions); $j++){
		$solutionObject = $authorSolutions[$j];
		$ownerURI = $solutionObject->owneruri;
		if ($ownerURI == $uri){
			$found = TRUE;
		}
	}
	if (!$found){
		array_push($problems, $allProblems[$i]);
	}
}

$totalResults = sizeof($problems);

$problemsPathArray = array();
$selectedChoiceArrey = array();
$problemsURI = array();
$categoriesArray = array();

$catCounterJson ='';

$catCounterJsonArray = array();
$mcCounterJsonArray = array();
//loop in all problems without solution by this user to retrieve URI path and 
//extract all the solutions for this problems by the individual student for MC histogram
for($i=0; $i<sizeof($problems); $i++){
	$problemObject = $problems[$i];
	$problemsPathArray[$i] = $problemObject->path;
	$problemsURI[$i] = $problemObject->uri;
	
	$solutionOwnerUri = $problemObject->uri;
	
	// An array for storing all solutions answer of specific problem Object
	$allMcCounter = array();
	foreach ($choicesArray as $curChoice){
		$allMcCounter[$curChoice] = 0;
	}
	
	// Extract all solutions with specific runId for MC histogram
	$solutionsQuery = 'type:Solution AND runid:' . $runId . ' AND owneruri:' . $rooloClient->escapeSearchTerm($solutionOwnerUri);
	$allSolutions = $rooloClient->search($solutionsQuery, 'metadata', 'latest');

	$solutionsArray = array();
	//Retrieve all eligible solutions for MC histogram
	for($idx=0; $idx<sizeof($allSolutions); $idx++){
		$solutionObject = $allSolutions[$idx];
		$author = strtolower($solutionObject->author);
		if (strstr($author , $mode) === FALSE){
			array_push($solutionsArray, $allSolutions[$idx]);
		}
	}

	for($k=0; $k< sizeof($solutionsArray); $k++){
		
		$curSolutionObject = $solutionsArray[$k];

		//add all MC answers of specific problem
		$allMcCounter[trim($curSolutionObject->selectedchoice)] += 1;
	}
	// Make json 
	$mcCounterJsonArray[$i] = json_encode($allMcCounter);
}

?>

<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>

<script type='text/javascript'>

	var questions = new Array('<?= implode('\', \'', $problemsPathArray)?>');

	var questionsURI = new Array('<?= implode('\', \'', $problemsURI)?>');

	var numQuestion = questions.length;

	var curQuestionNum = 1;

	var randomIndex = 0;

	var seconds = 01; 
	var minutes = 4;  
	var flag = "false";

	//to reset setTimeOut
//	var timer;

		var mcCounterArray = new Array();
		<?php 
			for ($i=0; $i< sizeof ($mcCounterJsonArray); $i++) {
		?>
				mcCounterArray[<?= $i ?>] = eval (<?= $mcCounterJsonArray[$i]?> );
		<?php 
			}
		?>
	
		mcCounter  = mcCounterArray[0];
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
			var questionUri = questionsURI[0];
			
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
						runId:"<?= $_SESSION['runId'] ?>",
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
							"<span style='margin-left: 5px; font-size: 10;'> -"+
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
	
	
//*****************************************************************	
	$(document).ready(function(){

//		randomIndex = randonCounter();
		$('#counter').val(randomIndex);

		if ('<?= $totalResults ?>' == 0){

			$('#middle-top').remove();
			$('#middle-center').remove();
			$('#middle-bottom').remove();
			
			$('#groupingMsgDiv').css({'width' : '70%', 'height' : '18%', 'margin-left':'15%'});
			
			groupingMsg = "<h2 style='width: 100%; float: center; Margin-top: 10%'><?= $noMoreProblemMsg1 ?></h2>";
			groupingMsg += "<h2 style='width: 100%; float: center'><?= $noMoreProblemMsg2 ?></h2>";
			$('#groupingMsgDiv').html(groupingMsg);
			$('#questionHeading').html();
			delay();
		}else{
		
			$('#curQuestion').attr('src', questions[randomIndex]);
			$('#questionHeading').html('<p><b>Question ' + curQuestionNum + ' of ' + numQuestion + '</b></p>');
			$('#timerValue').text( minutes + ":" + seconds );
			$('#timer').show();
//			countDown(); 
		}
	});

//	// this function counts the time down from 4:00 to 0:0
//	function countDown(){ 
//		if (seconds <= 0 || minutes <= -1){
//			
//			if (seconds <= 0){ 
//				    seconds = 59; 
//				    minutes -= 1; 
//			} 
//			if (minutes <= -1){ 
//			    seconds = 0; 
//			    minutes += 1; 
//			    saveQuestion();
//			}
//		}else{ 
//			seconds -= 1;
//		} 
//		if (seconds < 10)
//			$('#timerValue').text( minutes + ":0" + seconds ); 
//		else
//			$('#timerValue').text( minutes + ":" + seconds ); 
//		timer = setTimeout("countDown()",1000); 
//	 }
//
	 function delay (){
		setTimeout ("loginPage()", 10000);
	 }

	 function loginPage(){
		window.location = "/src/php/ajaxServices/logout.php";
	 } 
</script>
<script type='text/javascript'>

	function saveQuestion(){

		//disable the submit button
		$('#submitBtn').attr('disabled', 'disabled');

		//disable page and show message for next question
//		$('#timer').hide();
		$('#middle-top').hide();
		$('#middle-center').hide();
		$('#middle-bottom').hide();
		$('#nextQuestionMsgDiv').show();
//		clearTimeout(timer);

		//select the selected item in dropdowns
		var selectTagLength = $("#selectPart").find("select").length;
		var selectedOptions = new Array();
		
		for( i=1; i <= selectTagLength; i++){
			if ($("#select" + i ).attr("selectedIndex") != 0){
				selectedOptions.push($("#select" + i ).text($("#select" + i + " option:selected")));
			}
		}
		
		var counter = $('#counter').val();
		var selectedChoice = $("input[name='choice']:checked").val();
		var selectedCategory = new Array();
		$("input[name='categoryArray[]']:checked").each(function() {selectedCategory.push($(this).val());});
		var reason = $('#rationale').val();
		var category = selectedCategory.join(', ');
		var options = selectedOptions.join(', ');

		var categoryAndOptions;
		if (category != '' && options != '' )
			categoryAndOptions = category + ", " + options;
		if (category != '' && options == '' )
			categoryAndOptions = category;
		if (category == '' && options != '' )
			categoryAndOptions = options;
								
		//Ajax call to send group, selectedChoice, Owneruri, rationale
		$.get("/src/php/ajaxServices/saveMultiplechoice.php",
				{username:"<?= $_SESSION['username']?>",
				 runId:"<?= $_SESSION['runId'] ?>",
				 choice: selectedChoice,
				 ownerURI:questionsURI[counter],
				 flag: flag,
				 reason: reason,				 
				 category: category,
				 options: options,
				 categoryAndOptions: categoryAndOptions	
				},
		  		function(returned_data){
			  		// We don't need to do anything in the call-back function
			    }
		);

//		logout();

		//uncheck radiobuttons
		$("input[name='choice']:checked").attr("checked", false);

		//Clean the rationale textArea
		$('#rationale').val('');

		//uncheck checkBoxes
		$(".box").attr('checked', false);
					
		//reset dropDown boxes
		for( i=1; i <= selectTagLength; i++){
			$("#select" + i ).val(0);
		}

		// Delete the question which was shown and its URI from the arrays
		questions.splice(counter,1);
		questionsURI.splice(counter,1);
		flag = "false";

		//changes the question if it is not the last question
		if ( questions.length > 0 ){

//			counter = randonCounter();
			counter = 0;
			
			$('#counter').val(counter);
			$('#curQuestion').attr('src', questions[counter]);
	
			$('#submitBtn').removeAttr('disabled');

			//delete MC belong to last question and set MC for drawChart for next question
			mcCounterArray.splice(counter,1);
			mcCounter  = mcCounterArray[0];
			$('#rationalesReport').html('');
			$('#elementsReport').html('');
			drawChart();
		} else{
			logout();
		}

		// increment the current number of the question
		curQuestionNum++;
		if (curQuestionNum <= numQuestion) {
			$('#questionHeading').html('<p><b>Question ' + curQuestionNum + ' of ' + numQuestion + '</b></p>');
		}
	}

	function logout () {

		$('#nextQuestionMsgDiv').remove();
		$('#middle-top').remove();
		$('#middle-center').remove();
		$('#middle-bottom').remove();
		$('#signOut').remove();
		
		$('#groupingMsgDiv').css({'width' : '70%', 'height' : '18%', 'margin-left':'15%'});

		groupingMsg = "<h2 style='width: 100%; float: center; Margin-top: 10%'><?= $noMoreProblemMsg1 ?></h2>";
		groupingMsg += "<h2 style='width: 100%; float: center'><?= $noMoreProblemMsg2 ?></h2>";

		$('#groupingMsgDiv').html(groupingMsg);
		$('#questionHeading').html('');
		$('#signOut').html('');
		delay();
	}

	//function generate the counter value (the index of curent question in 
	//questions and questionsURI arraies)
	function randonCounter(){
		adjustedHigh = questions.length;
        return Math.floor(Math.random()*adjustedHigh);
	}

	function check(){
		var choice = $("input[name='choice']:checked").val();
		if (choice != null && choice.length != 0){
			flag = "true";
			saveQuestion();
		}else{	
			alert("Please select the correct answer!");
		}
	}

	function nextQuestion(){
		
		$('#nextQuestionMsgDiv').hide();
		$('#middle-top').show();
		$('#middle-center').show();
		$('#middle-bottom').show();
	
//		//set the clock for next question
//		seconds = 01; 
//		minutes = 4; 
//		$('#count').text( minutes + ":" + seconds + "time left"); 
//		$('#timer').show();
//		countDown(); 
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
		height:2200px;
	}
	div#answerSection{
		top:0;
	} 
	div#mcReport {
		top:20px;
	}
	 

</style>

<div id="container2">

<div id="middle-top">
	<div id="questionHeading"><p><b></b></p></div>
<!--	<div id="timer" style='display: none'>-->
<!--    	<p>TIME REMAINING</p>-->
<!--    	<h1 id='timerValue'></h1>-->
<!--  	</div>-->
	
</div>

<div id="nextQuestionMsgDiv" style="display:none">

	<label><h3>Please listen to your teacher !!! </label>
	<input name="nextQuestion" value="Next Question" type="button" onClick="nextQuestion(); scroll(0,0);" />
</div>

<div id='groupingMsgDiv'></div>

<div id="middle-center" >
    <div id="questionSection">
    	<img id='curQuestion' src="" width="454" height="320" class="problem"/>
  	</div>
  	
	<div id='mcReport' >
	</div>
	<div id='elementsReport'>
		Please click on a Multiple Choice bar first
	</div>
	<div id='rationalesReport'>
		
	</div>	
	<div id="answerSection" >
		<form id="round1" name="form1" method="post" action="feedback.php">
			<dl id='mc'>
				<dt>1.Select the correct answer:</dt>
				<dd>
					<?php 
						foreach ($choicesArray as $choice) {
					?>
							<label class="radioButton"><input type="radio" name="choice" value="<?= trim($choice) ?>" /><?= trim($choice) ?></label>
					<?php		
						}
					?>
				</dd>
			</dl>
			<dl id='elements'>
				<dt>2. Check the corresponding elements that you thought were important in answering the question:</dt>

			  	<dd>
			  		<?php 
						foreach (Application::$problemCategories as $curProblemCategory) {
					?>
						  		<label><input class="box" type="checkbox" name="categoryArray[]" value="<?= $curProblemCategory?>" /><?= $curProblemCategory?></label><br/>
					<?php 
						}
					?>
			  	</dd>
			  	<dd id="selectPart">
			  		<?php 
			  			$i = 0;
						foreach (Application::$dropdownsItems as $dropdownItems) {
								$i++;
					?>
								<select id="select<?= $i?>" >
										<?php 
											foreach ($dropdownItems as $item) {
										?>
												<option><?= $item?></option>
										<?php 
											}
										?>		 								
								</select>
					<?php 
						}
					?>
			 	</dd>
			 	<dt> 3. Provide a reasoning for the elements that you chose above: </dt>			  	
				<textarea id="rationale" rows="20" cols="40"></textarea>		  
			 	
			  	<input name="submit2" type="button" value="SUBMIT" class="btn" onClick="check(); scroll(0,0);" />
			  	
			</dl>
		</form>
	</div> <!-- id="answerSection" -->
</div><!-- id="middle-center"> -->	
<div id="middle-bottom"></div>
 
 
<label><input type='hidden' id='counter' name='counter' value=""/></label>

<?php 

require_once './footer.php';
?>