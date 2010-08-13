<?php
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';
require_once '../dataModels/Solution.php';

error_reporting(E_ALL | E_STRICT);

if (!$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
//	$_SESSION['loggedIn'] = true;
//	$_SESSION['username'] = $_REQUEST['username'];
}

$_SESSION['msg'] = "";
$username = $_SESSION['username'];
$greetingMsg = "Signed in as <b> " . $username . "</b>";


$roolo = new RooloClient();
/*
 * Extract all the questions
 */
$uniqueQuestionIds = Application::$groupQuestions[$username];
$uniqueQuestionIdStr = implode(' OR ', $uniqueQuestionIds);
$query = "type:Problem AND uniquequestionid:(" . $uniqueQuestionIdStr . ")";
$allQuestions = $roolo->search($query, 'metadata', 'latest');

$solurinQuery = "type:Solution AND author:" . $_SESSION['username'];
$authorSolutions = $roolo->search($solurinQuery, 'metadata');

$noMoreProblemMsg1 = 'Thanks!';
$noMoreProblemMsg2 = 'You will be logged out in 10 seconds. Please wait for instructions...';

$solutionObject = new Solution();
$QuestionObject = new Problem();
$questions = Array();

for($i=0; $i<sizeof($allQuestions); $i++){
	$QuestionObject = $allQuestions[$i];
	$uri = $QuestionObject->uri;
	$found = FALSE;
	for($j=0; $j<sizeof($authorSolutions); $j++){
		$solutionObject = $authorSolutions[$j];
		$ownerURI = $solutionObject->owneruri;
		if ($ownerURI == $uri){
			$found = TRUE;
		}
	}
	if (!$found){
		array_push($questions, $allQuestions[$i]);
	}
}

$totalResults = sizeof($questions);
$mcCounterJson = '';

$problems = array();
$problemsURI = array();

if ( $totalResults != 0){
	for ($i=0; $i< $totalResults; $i++){
		$problemObject = new Problem();
		$problemObject = $questions[$i];
		$problems[$i] = $problemObject->path;
		$problemsURI[$i] = $problemObject->uri;
	}
	$aggregateSolutionsMode = 'GROUP';
	// Find all the solutions and return suitable data 
	require_once '../ajaxServices/aggregateSolutions.php';
}
?>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">

	mcCounter  = eval(<?= $mcCounterJson?>);
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
						mcChoice:selectedMc
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
	
	function check(){
		var choice = $("input[name=choice]:checked");
		if (choice.length != 0){
			nextQuestion();
		}else{	
			alert ("Please select the correct answer!");
		}
	}		

	function logout() {
		$('#middle-top').remove();
		$('#middle-center2').remove();
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

	function delay(){
		setTimeout ("loginPage()", 10000);
	}
	
	function loginPage(){
		window.location = "/src/php/ajaxServices/logout.php";
	}
	 
	function nextQuestion(){
	
		//disable the submit button
		$('#submitBtn').attr('disabled', 'disabled');
	
		var reason = $('#resonTextarea').val();
		var selectedChoice = $("input[name=choice]:checked").val();
		var questionUri = questionsURI[0];
		var selectedCategory = new Array();
		$("input[name='categoryArray[]']:checked").each(function() {selectedCategory.push($(this).val());});
		var category = selectedCategory.join(', ');
					
		// Delete the shown question and its URI from arraies
		questions.splice(0,1);
		questionsURI.splice(0,1);

		//change question, draw graph and save the solution
		if (questions.length >= 0){
			// ****************************** write to roolo
			//Ajax call to send groupname, selectedChoice, Owneruri, reason and category
			$.get("/src/php/ajaxServices/saveMultiplechoice.php",
					{username:"<?= $_SESSION['username']?>",
					 choice: selectedChoice,
					 ownerURI:questionUri,
					 reason: reason,
					 //questionPath: questions[counter],
					 category: category,
					 flag: flag
					},
			  		function(returned_data){
				  		// We don't need to do anything in the call-back function
				    }
			);

			logout();
		}
		
	}
	
	var questions = new Array('<?= implode('\', \'', $problems)?>');
	var questionsURI = new Array('<?= implode('\', \'', $problemsURI)?>');
	var numQuestion = questions.length;

	var curQuestionNum = 1;

	var curQuestionIndex = 0;

	var seconds = 31; 
	var minutes = 2;  
	var reasonTextMax = 300; //Max length for reason string in texarea 
	var flag = "false";

	$(document).ready(function(){
		
		if ('<?= $totalResults ?>' == 0){
			window.location.href= "/src/php/pages/longAnswerConceptChoice.php";
		}else {
			$('#curQuestion').attr('src', questions[0]);
			$('#questionHeading').html('<p><b>Question ' + curQuestionNum + ' of ' + numQuestion + '</b></p>');
			$('#timerValue').text( minutes + ":" + seconds );
			$('#charLeftStr').text( reasonTextMax + " characters left");
			$('#timer').show();
		}

        $('#resonTextarea').keyup(function() {
            var len = this.value.length;
            if (len >= reasonTextMax) {
                this.value = this.value.substring(0, 300);
            }
            var leftChar = (reasonTextMax - len);
            if(leftChar < 0){
                msg = "  " + " 0 character left";
            }else if(leftChar > 1){
                msg = "  " + leftChar + " characters left";
            }else {
                msg = "  " + leftChar + " character left";
            }
            $('#charLeftStr').text(msg);
        });
	});
</script>
	

 <div id='container2'>
	<div id="middle-top">
		<div id='questionHeading'></div>
	</div>
	<div id='groupingMsgDiv'></div>
	<div id='middle-center2'>
		<div id='questionSection'>
			<p>Below are the multiple choice report and elements report for this question.
		       Review this information and discuss as a group submitting your answers.</p>
			<img class='problem' id='curQuestion' height="320" width="454" src='' />
		</div>
		<div id='mcReport'>
		</div>
		<div id='elementsReport'>
			Please click on a Multiple Choice bar first
		</div>
		<div id='rationalesReport'>
			
		</div>	
		<div id="answerSection2">
			<form id="round1" name="form1" method="post" action="feedback.php">
				<dl>
					<dt>1.Select the correct answer:</dt>
				    <dd><label class="radioButton"><input type="radio" name="choice" value="a"/>A</label>
				 		<label class="radioButton"><input type="radio" name="choice" value="b"/>B</label>
				  		<label class="radioButton"><input type="radio" name="choice" value="c"/>C</label>
				  		<label class="radioButton"><input type="radio" name="choice" value="d"/>D</label>
				  		<label class="radioButton"><input type="radio" name="choice" value="e"/>E</label>
				  		<label class="radioButton"><input type="radio" name="choice" value="f"/>F</label>
				  		<label class="radioButton"><input type="radio" name="choice" value="g"/>G</label>
				  	</dd>
		
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
				  
				   	<dt>3. Write a rationale explaining why you selected the elements above.</dt>
				  	<dd><textarea id="resonTextarea" name="" cols="35" rows="12"></textarea></dd>
					<p id="charLeftStr" ></p>
				  	<input name="submit" type="button" value="SUBMIT" class="btn" onClick="check(); scroll(0,0);" />
				</dl>
				<input type='hidden' id='' name='counter' value=""/>			  	
			</form>
		</div> <!-- id="answerSection2" -->
	</div> <!-- id='middle-center2' -->
	<div id='middle-bottom'>
	</div>
</div><!-- id='container2'-->
<!--<div class="clearfooter"/>-->

<?php 
require_once './footer.php';
?>
