<?php
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';
require_once '../dataModels/Solution.php';

error_reporting(E_ALL | E_STRICT);

if (!$_SESSION['loggedIn'])
	header("Location:/src/php/pages/");
$_SESSION['loggedIn'] = FALSE;	
$_SESSION['msg'] = "";
$greetingMsg = "Signed in as <b> " . $_SESSION['username'] . "</b>";
$noMoreProblemMsg = 'You have finished answering all the questions.';


$roolo = new RooloClient();
/*
 * Extract all the questions
 */
$allQuestions = $roolo->search('type:Problem', 'metadata', 'latest');

$solurinQuery = "type:Solution AND author:" . $_SESSION['username'];
$authorSolutions = $roolo->search($solurinQuery, 'metadata');

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
$catCounterJson ='';
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
	// Find all the solutions and return suitable data 
	require_once '../ajaxServices/aggregateSolutions.php';
}
?>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">

	catCounter = eval(<?= $catCounterJson?>);
	mcCounter  = eval(<?= $mcCounterJson?>);

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
		var chart = new google.visualization.ColumnChart(document.getElementById('mcReport'));
		chart.draw(data, {
			width: 350, 
			height: 300, 
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
		var chart = new google.visualization.ColumnChart(document.getElementById('elementsReport'));
		chart.draw(data, {
			width: 820, 
			height: 300, 
			is3D: true, 
			title: 'Category Report'});
	}

	function check(){
		var choice = $("input[name='choice']:checked").val();
		if (choice == 'A' || choice == 'B' || choice == 'C' || choice == 'D' || choice == 'E'){
			nextQuestion();
		}else{	
			alert ("Please select the corect answer!");
		}
	}		

	function nextQuestion(){
	
		//disable the submit button
		$('#submitBtn').attr('disabled', 'disabled');
	
		var reason = $('#resonTextarea').val();
		var selectedChoice = $("input[name='choice']:checked").val();
		var questionUri = questionsURI[0];
		var selectedCategory = new Array();
		$("input[name='categoryArray[]']:checked").each(function() {selectedCategory.push($(this).val());});
		var category = selectedCategory.join(', ');
					
		// Delete the showed question and its URI from arraies
		questions.splice(0,1);
		questionsURI.splice(0,1);

		//change question, draw graph and save the solution
		if (questions.length >= 0){
			if ( questions.length  > 0 ){
	
				//counter = randonCounter();
				counter = questions.length;
				
				$('#counter').val(counter);
		
				//$('#curQuestion').attr('src', questions[counter]);
				$('#curQuestion').attr('src', questions[0]);
		
				// delete last graphs
				$('#mcReport').html('');
				$('#elementsReport').html('');
				// delete content of resone 
				$('#resonTextarea').val('');
				$('#charLeftStr').text( reasonTextMax + " characters left");
		
				//uncheck radiobuttons
				$("input[name='choice']:checked").attr("checked", false);
		
				//uncheck checkBoxes
				$(".box").attr('checked', false);
		
				//****************************** draw graph for question
				//Ajax call to send problemUri and deaw graph for this question
				$.get("/src/php/ajaxServices/aggregateSolutions.php",
					{problemUri:questionsURI[0]},
			  		function(returned_data){
						dataArray = eval(returned_data);
						mcCounter  = dataArray[0];
						catCounter = dataArray[1];
		
						//////////////////////////////////////// MULTIPLE CHOICE ///////////////////////////////////////////////
						var data = new google.visualization.DataTable();
						data.addColumn('string', 'Multiple Choice');
						data.addColumn('number', '# of Times Selected');
		
						for (var key in mcCounter){
							data.addRow([key, mcCounter[key]]);
						}
						// Instantiate and draw our chart, passing in some options.
						var chart = new google.visualization.ColumnChart(document.getElementById('mcReport'));
						chart.draw(data, {
							width: 350, 
							height: 300, 
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
						var chart = new google.visualization.ColumnChart(document.getElementById('elementsReport'));
						chart.draw(data, {
							width: 820, 
							height: 300, 
							is3D: true, 
							title: 'Category Report'});
					}
				);
	
		
				$('#submitBtn').removeAttr('disabled');
		
	//			seconds = 31; 
	//			minutes = 2; 
	//			$('#count').text( minutes + ":" + seconds + "time left"); 
	
			}
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
				
		}
		if (questions.length == 0){
		
				$('#middle-top').remove();
				$('#middle-center2').remove();
				$('#middle-bottom').remove();
				
				$('#groupingMsgDiv').css({'width' : '70%', 'height' : '18%', 'margin-left':'15%'});
		
				groupingMsg = "<h2 style='width: 100%; float: left; margin-top: 10%'> <?= $noMoreProblemMsg ?> </h2>";
		
				$('#groupingMsgDiv').html(groupingMsg);
				$('#questionHeading').html('');
				$('#signIn').html('');
				delay();
		}
	
		// increment the current number of the question
		curQuestionNum++;
		if (curQuestionNum <= numQuestion) {
			$('#questionHeading').html('<p><b>Question ' + curQuestionNum + ' of ' + numQuestion + '</b></p>');
		}
	}
</script>


<script type='text/javascript'>

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
		
		signOutLink = '<a onClick="document.location.href=\'/src/php/ajaxServices/logout.php\'" style="cursor:pointer;"> Sign Out </a>';
		$('#signIn').html('<?= $greetingMsg?>' + " | " + signOutLink);

		if ('<?= $totalResults ?>' == 0){

			$('#middle-top').remove();
			$('#middle-center2').remove();
			$('#middle-bottom').remove();
			
			$('#groupingMsgDiv').css({'width' : '70%', 'height' : '18%', 'margin-left':'15%'});
			
			groupingMsg = "<h2 style='width: 100%; float: left; Margin-top: 10%'><?= $noMoreProblemMsg ?></h2>";
			$('#groupingMsgDiv').html(groupingMsg);
			$('#questionHeading').html();
			delay();
		}else {
			$('#curQuestion').attr('src', questions[0]);
			$('#questionHeading').html('<p><b>Question ' + curQuestionNum + ' of ' + numQuestion + '</b></p>');
			$('#timerValue').text( minutes + ":" + seconds );
			$('#charLeftStr').text( reasonTextMax + " characters left");
			$('#signin').show();
			$('#timer').show();
//			countDown(); 
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

//	// this function counts the time down from 2:30 to 0:0
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
//			    nextQuestion();
//			}
//		}else{ 
//			seconds -= 1;
//		} 
//		if (seconds < 10)
//			$('#timerValue').text( minutes + ":0" + seconds ); 
//		else
//			$('#timerValue').text( minutes + ":" + seconds ); 
//		setTimeout("countDown()",1000); 
//	 }

	 function delay (){
		setTimeout ("loginPage()", 10000);
	 }

	 function loginPage(){
		window.location = "/src/php/pages/";
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
		</div>	
		<div id="answerSection2">
			<form id="round1" name="form1" method="post" action="feedback.php">
				<dl>
					<dt>1.Select the corect answer:</dt>
				    <dd><label class="radioButton"><input type="radio" name="choice" value="A"/>A</label>
				 		<label class="radioButton"><input type="radio" name="choice" value="B"/>B</label>
				  		<label class="radioButton"><input type="radio" name="choice" value="C"/>C</label>
				  		<label class="radioButton"><input type="radio" name="choice" value="D"/>D</label>
				  		<label class="radioButton"><input type="radio" name="choice" value="E"/>E</label>
				  	</dd>
		
					<dt>2.Check the corresponding elements that are shown in the problem:</dt>
				  	<dd>
				  		<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="net force"/>Net force</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="One body problem"/>One body problem</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="Multiple body problem"/>Multiple body problem</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="Collision"/>Collision</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="Explosion"/>Explosion</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="Fast or instantaneous process"/>Fast or instantaneous process</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="1 dimensional"/>1 dimensional</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="2 dimensional"/>2 dimensional</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="closed system"/>Closed system</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="open system"/>Open system</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="conserved"/>Conserved</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="not conserved"/>Not conserved</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="energy"/>Energy</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="momentum"/>Momentum</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="impulse"/>Impulse</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="force"/>Force</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="displacement"/>Displacement</label><br/>
						<label><input type="checkbox" class="box" name="categoryArray[]" id="" value="velocity"/>Velocity</label><br/>
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
