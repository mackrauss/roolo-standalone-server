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

/*
 * Get TeacherProgress up to this point
 */
$results = $roolo->search('type:TeacherProgress', 'metadata', 'latest');
if (!isset($results[0])){
	echo "ERROR: TeacherProgress object doesn't exist in DB";
	die();
}
$teacherProgress = $results[0];
$teacherProgressUris = explode(',',$teacherProgress->get_progress());

$noMoreProblemMsg1 = 'Thanks!';
$noMoreProblemMsg2 = 'You will be logged out momentarily.';

$solutionObject = new Solution();
$QuestionObject = new Problem();
$questions = Array();

for($i=0; $i<sizeof($allQuestions); $i++){
	$QuestionObject = $allQuestions[$i];
	$uri = $QuestionObject->uri;
	$found = FALSE;
	for($j=0; $j<sizeof($teacherProgressUris); $j++){
		$curProgressUri = $teacherProgressUris[$j];
		if ($curProgressUri == $uri){
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
$problemMasterSolutions = array();
$problemsURI = array();

if ( $totalResults != 0 ){
	for ($i=0; $i< $totalResults; $i++){
		$problemObject = new Problem();
		$problemObject = $questions[$i];
		$problems[$i] = $problemObject->path;
		$problemMasterSolutions[$i] = $problemObject->get_mcmastersolution();
		$problemsURI[$i] = $problemObject->uri;
	}
	$aggregateSolutionsMode = 'SUPERGROUP';
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
						mcChoice:selectedMc,
						mode:'SUPERGROUP'
					},
			  		function(data){
						recreateCatChart(eval('('+data+')'));
					});
		});

		  
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
	var mcMasterSolution = '<?= $problemMasterSolutions[0] ?>';
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

	function showMcMasterSolution(){
		$('#mcMasterSolutionContainer').html(mcMasterSolution);
	}

	function updateTeacherProgress(){
		var questionUri = questionsURI[0];
		$.get(	'/src/php/ajaxServices/updateTeacherProgress.php', 
				{
					'questionUri':questionUri
				},
				function(response){
					
				}
		);

		logout();
	}
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
		<div style='float: left; width: 60%; margin-top: 60px;	 margin-left: 60px;'>
			<input type='button' onclick='showMcMasterSolution()' value='Show Correct Answer' />
			<div id='mcMasterSolutionContainer' style='width: 120px; height: 40px; border: 3px solid red; vertical-align: middle; text-align: center; font-size: 30px; margin-top: 15px;'>
				
			</div>
		</div>
		<div style='float: left; width: 20%; margin-top: 60px;	 margin-left: 80px;'>
			<input type='button' onclick='updateTeacherProgress()' value='Done with this question' />
		</div>
	</div> <!-- id='middle-center2' -->
	<div id='middle-bottom'>
	</div>
</div><!-- id='container2'-->
<!--<div class="clearfooter"/>-->

<?php 
require_once './footer.php';
?>
