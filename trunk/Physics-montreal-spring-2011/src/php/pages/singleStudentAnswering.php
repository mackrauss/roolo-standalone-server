<?php

/**
 * This file generates the page for a single student to answer an MC question, tag it with appropriate elements and provide a rationale.
 * Then the student will be asked to move to the next question or not.
 */

/*
 * Version #2 (dual mode � starts as homework and continues with in-class group work) (Very similar to Version #1)
 *
 * B)Students will be given a set of questions pre-set by the instructor on the Teacher Upload/Administer page
 *    Order of steps for students:
 *	
 *	 	  i) Students solve the Multiple choice question
 *		
 *		 ii) Students write rationale for answer
 *		
 *		iii) Students tag elements
 *		
 *		 iv) Students repeat steps without logging out for all questions
 *		
 *		  v) Students will be limited to 4 minutes which will be shown with a count down timer
 *		
 *	The system then prompts the student to log out and wait for feedback during their class.
 * 
 */
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';
require_once '../Application.php';
require_once '../dataModels/RunConfig.php';

error_reporting(E_STRICT);
if (!$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
//	$_SESSION['loggedIn'] = true;
//	$_SESSION['username'] = $_REQUEST['username'];
}

$_SESSION['msg'] = "";
$username = $_SESSION['username'];
$runId = $_SESSION['runId'];
$greetingMsg = "Signed in as <b> " . $username . "</b>";
$noMoreProblemMsg1 = 'Great job completing this step!';
$noMoreProblemMsg2 = 'You will be logged out in 10 seconds.';

//if(isset($_GET['username'])){
//	$_SESSION['username'] = $_GET['username'];
//	$greetingMsg = "Hello " . $_SESSION['username'];
//}else {
//	$_SESSION['username'] = '';
//	$greetingMsg = 'username has not been set !!!';
//}

$rooloClient = new RooloClient();

//Retrive related RunConfig elo for runchoicelimit element
$runConfigObj = new RunConfig();
$runConfigQuery = "type:RunConfig AND runid:" . $runId;
$runConfigArray = $rooloClient->search($runConfigQuery, 'metadata');
$runConfigObj = $runConfigArray[0];
$runChoiceLimit = $runConfigObj->runchoicelimit;
$choicesArray = explode( ","  , $runChoiceLimit );

$query ='';
$results = array();

//Retrieve all problems object for specific runID
$query = "type:Problem AND runid:" . $runId ;
$allProblems = $rooloClient->search($query, 'metadata', 'latest');

//Determine all problems object without solutions for the specific user
//$query = "type:Solution AND author:" . $username;
$query = "type:Solution AND author:" . $username . " AND runid:" . $runId;
$authorSolutions = $rooloClient->search($query, 'metadata');

$solutionObject = new Solution();
$problemObject = new Problem();

for($i=0; $i<sizeof($allProblems); $i++){
	$problemObject = $allProblems[$i];
	$uri = $problemObject->uri;
	$found = FALSE;
	for($j=0; $j<sizeof($authorSolutions); $j++){
		$solutionObject = $authorSolutions[$j];
		$ownerURI = $solutionObject->owneruri;
		//echo "URI =
		if ($ownerURI == $uri){
			$found = TRUE;
		}
	}
	if (!$found){
		array_push($results, $allProblems[$i]);
	}
}

$totalResults = sizeof($results);
//echo "total = ". $totalResults;
//die();
$problems = array();
$problemsURI = array();

if ($totalResults != 0){
	for ($i=0; $i< $totalResults; $i++){
		$problemObject = new Problem();
		$problemObject = $results[$i];
		$problems[$i] = $problemObject->path;
		$problemsURI[$i] = $problemObject->uri;
	}
}
//echo "path = " . $problems[0];
//die(); 
?>

<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<script type='text/javascript'>

	var questions = new Array('<?= implode('\', \'', $problems)?>');

	var questionsURI = new Array('<?= implode('\', \'', $problemsURI)?>');

	var numQuestion = questions.length;

	var curQuestionNum = 1;

	var randomIndex = 0;

//	var seconds = 01; 
//	var minutes = 4;  
	var flag = "false";

//	//to reset setTimeOut
//	var timer;
//	var timer_is_On = true;

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
//			$('#timerValue').text( minutes + ":" + seconds );
//			$('#timer').show();
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
//				timer_is_On = false;
//			    clearTimeout(timer); 
//			    saveQuestion();
//			}
//		}else{ 
//			seconds -= 1;
//		} 
//		if (seconds < 10)
//			$('#timerValue').text( minutes + ":0" + seconds ); 
//		else
//			$('#timerValue').text( minutes + ":" + seconds ); 
//		if (timer_is_On)
//			timer = setTimeout("countDown()",1000); 
//	 }

	 function delay (){
		setTimeout ("loginPage()", 10000);
	 }

	 function loginPage(){
		window.location = "/src/php/ajaxServices/logout.php";
	 } 
</script>
<script type='text/javascript'>

	function saveQuestion(){
		//select the selected item in dropdowns
		var options = $("#selectPart").find("input[type:'radio']:checked").map(function(){
			   return $(this).val();
		}).get().join(", ");

		var counter = $('#counter').val();
		var selectedChoice = $("input[name='choice']:checked").val();
		if (selectedChoice == null || selectedChoice.length == 0){
			alert("Please choose an answer");
			return;
		}
		var selectedCategory = new Array();
		$("input[name='categoryArray[]']:checked").each(function() {selectedCategory.push($(this).val());});
		var reason = $('#rationale').val();
		if (reason == ''){
			alert("Please provide a reasoning for your answer");
			return;
		}
		var category = selectedCategory.join(', ');

		var categoryAndOptions;
		if (category != '' && options != '' )
			categoryAndOptions = category + ", " + options;
		if (category != '' && options == '' )
			categoryAndOptions = category;
		if (category == '' && options != '' )
			categoryAndOptions = options;

		// OK WE ARE NOW CLEAR TO SUBMIT THE ANSWER AND SHOW THE PAURSE PAGE
		//disable the submit button
		$('#submitBtn').attr('disabled', 'disabled');

		//disable page and show message for next question
//		$('#timer').hide();
		$('#middle-top').hide();
		$('#middle-center').hide();
		$('#middle-bottom').hide();
//		$('#questionHeading').html('');
		$('#nextQuestionMsgDiv').show();
//		clearTimeout(timer);


							
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
		$("input[type='radio']:checked").attr("checked", false);

		//Clean the rationale textArea
		$('#rationale').val('');

		//uncheck checkBoxes
		$(".box").attr('checked', false);
					
		//reset dropDown boxes
//		for( i=1; i <= selectTagLength; i++){
//			$("#select" + i).each(function(idx){
//				console.log(this);
//			});
//		}
		$('#round1').get(0).reset();

		// Delete the question which was shown and its URI from the arrays
		questions.splice(counter,1);
		questionsURI.splice(counter,1);
		flag = "false";
		
		//changes the question if it is not the last question
		if ( questions.length > 0 ){
			counter = 0;
			
			$('#counter').val(counter);
			$('#curQuestion').attr('src', questions[counter]);
				
			$('#submitBtn').removeAttr('disabled');
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

	function showMcPart(){
		flag = "true";
		$('#elements').hide('slow');
		$('#mc').show('slow');
		
//		var choice = $("input[name='choice']:checked").val();
//		if (choice != null && choice.length != 0){
//			flag = "true";
//			$('#mc').hide('slow');
//			$('#elements').show('slow');
//			$('#elements').hide('slow');
//			$('#mc').show('slow');
//		}else{	
//			alert("Please select the correct answer!");
//		}
	}

	function nextQuestion(){
		
		$('#nextQuestionMsgDiv').hide();
		$('#middle-top').show();
		$('#middle-center').show();
		$('#middle-bottom').show();

		//hide the mc part and show the elements part 
		$('#mc').hide('slow');
		$('#elements').show('slow');
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
	 

</style>

<div id="container2">

<div id="middle-top">
	<div id="questionHeading"><p><b></b></p></div>
<!--	<div id="timer">-->
<!--    	<p>TIME REMAINING</p>-->
<!--    	<h1 id='timerValue'></h1>-->
<!--  	</div>-->
	
</div>

<div id="nextQuestionMsgDiv" style="display:none">

	<label><h3>Please continue to the next question when you're ready.</label>
	<input name="nextQuestion" value="Next Question" type="button" onClick="nextQuestion(); scroll(0,0);" />
</div>

<div id='groupingMsgDiv'></div>

<div id="middle-center">
    <div id="questionSection">
    	<img id='curQuestion' src="" width="454" height="320" class="problem"/>
  	</div>
	<div id="answerSection" >
		<form id="round1" name="form1" method="post" action="feedback.php">
			<dl id='elements' >
				<dt>1. Check the corresponding elements that you thought were important in answering the question:</dt>

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
						foreach (Application::$dropdownsItems as $dropdownItemsKey => $dropdownItems) {
								$i++;
								echo "<b>" . $dropdownItemsKey. "</b> <br/>";
					?>
								<?php 
									for ($j=1; $j < sizeof($dropdownItems); $j++){
								?>
										<input type="radio" name="<?= $dropdownItemsKey?>" value="<?= $dropdownItems[$j]?>" /> <?= $dropdownItems[$j] ?> <br/>
								<?php 
									}
								?>
								<br/>
					<?php 
						}
					?>
			 	</dd>
			 	<input name="submit" type="button" value="NEXT PAGE" class="btn" onClick="showMcPart(); scroll(0,0);" />
			</dl>
			<dl id='mc' style='display: none'>
				<dt>2.Select the correct answer:</dt>
				<dd>
					<?php 
						foreach ($choicesArray as $choice) {
					?>
							<label class="radioButton"><input type="radio" name="choice" value="<?= trim($choice) ?>" /><?= trim($choice) ?></label>
					<?php		
						}
					?>
				</dd>
				<dt> 3. Provide a reasoning for the answer that you chose above: </dt>			  	
				<textarea id="rationale" rows="20" cols="40"></textarea>
				
				<input name="submit2" type="button" value="SUBMIT" class="btn" onClick="saveQuestion(); scroll(0,0);" />		  
			  	
			</dl>
		</form>
	</div> <!-- id="answerSection" -->
</div><!-- id="middle-center"> -->	
<div id="middle-bottom"></div>
 
 
<label><input type='hidden' id='counter' name='counter' value=""/></label>

<?php 

require_once './footer.php';
?>