<?php

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';
require_once '../Application.php';

error_reporting(E_STRICT);
if (!$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
//	$_SESSION['loggedIn'] = true;
//	$_SESSION['username'] = $_REQUEST['username'];
}

$_SESSION['msg'] = "";
$username = $_SESSION['username'];
$greetingMsg = "Signed in as <b> " . $username . "</b>";
$noMoreProblemMsg1 = 'Great job completing step 1!';
$noMoreProblemMsg2 = 'You will be logged out in 10 seconds. Please log in again with your group ID.';

//if(isset($_GET['username'])){
//	$_SESSION['username'] = $_GET['username'];
//	$greetingMsg = "Hello " . $_SESSION['username'];
//}else {
//	$_SESSION['username'] = '';
//	$greetingMsg = 'username has not been set !!!';
//}

// retrieve questions from repository
$rooloClient = new RooloClient();
$query ='';
$results = array();

//Retrieve all problems object
$uniqueQuestionIds = Application::$studentQuestions[$username];
$uniqueQuestionIdStr = implode(' OR ', $uniqueQuestionIds);
$query = "type:Problem AND uniquequestionid:(" . $uniqueQuestionIdStr . ")";
$allProblems = $rooloClient->search($query, 'metadata', 'latest');

//Determine all questions object without solutions for the specific user
$query = "type:Solution AND author:" . $username;
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
		if ($ownerURI == $uri){
			$found = TRUE;
		}
	}
	if (!$found){
		array_push($results, $allProblems[$i]);
	}
}

$totalResults = sizeof($results);

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
?>

<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<script type='text/javascript'>

	var questions = new Array('<?= implode('\', \'', $problems)?>');

	var questionsURI = new Array('<?= implode('\', \'', $problemsURI)?>');

	var numQuestion = questions.length;

	var curQuestionNum = 1;

	var randomIndex = 0;

	var seconds = 31; 
	var minutes = 2;  
	var flag = "false";

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
		window.location = "/src/php/ajaxServices/logout.php";
	 } 
</script>
<script type='text/javascript'>

	function nextQuestion(){

		//disable the submit button
		$('#submitBtn').attr('disabled', 'disabled');

		var counter = $('#counter').val();
		var selectedChoice = $("input[name='choice']:checked").val();
		var selectedCategory = new Array();
		$("input[name='categoryArray[]']:checked").each(function() {selectedCategory.push($(this).val());});
		var category = selectedCategory.join(', ');
		var reason = $('#rationale').val();

		//Ajax call to send group, selectedChoice, Owneruri, rationale
		$.get("/src/php/ajaxServices/saveMultiplechoice.php",
				{username:"<?= $_SESSION['username']?>",
				 choice: selectedChoice,
				 ownerURI:questionsURI[counter],
				 flag: flag,
				 category: category,
				 reason: reason				 
				},
		  		function(returned_data){
			  		// We don't need to do anything in the call-back function
			    }
		);

		logout();

		//uncheck radiobuttons
		$("input[name='choice']:checked").attr("checked", false);

		//uncheck checkBoxes
		$(".box").attr('checked', false);
					
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

			//uncheck radiobuttons
			$("input[name='choice']:checked").attr("checked", false);

			//uncheck checkBoxes
			$(".box").attr('checked', false);

			$('#submitBtn').removeAttr('disabled');

			seconds = 31; 
			minutes = 2; 
			$('#count').text( minutes + ":" + seconds + "time left"); 

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
		if (choice.length != 0){
			flag = "true";
			nextQuestion();
		}else{	
			alert ("Please select the correct answer!");
		}
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
<!--	<div id="timer" style='display: none'>-->
<!--    	<p>TIME REMAINING</p>-->
<!--    	<h1 id='timerValue'></h1>-->
<!--  	</div>-->
	
</div>

<div id='groupingMsgDiv'></div>

<div id="middle-center">
    <div id="questionSection">
    	<img id='curQuestion' src="" width="454" height="320" class="problem"/>
  	</div>
	<div id="answerSection">
		<form id="round1" name="form1" method="post" action="feedback.php">
			<dl>
				<dt>1.Select the correct answer:</dt>
			    <dd><label class="radioButton"><input type="radio" name="choice" value="A"/>A</label>
			 		<label class="radioButton"><input type="radio" name="choice" value="B"/>B</label>
			  		<label class="radioButton"><input type="radio" name="choice" value="C"/>C</label>
			  		<label class="radioButton"><input type="radio" name="choice" value="D"/>D</label>
			  		<label class="radioButton"><input type="radio" name="choice" value="E"/>E</label>
			  		<label class="radioButton"><input type="radio" name="choice" value="F"/>F</label>
			  		<label class="radioButton"><input type="radio" name="choice" value="G"/>G</label>
			  		<label class="radioButton"><input type="radio" name="choice" value="H"/>H</label>
			  	</dd>
	
				<dt>2.Check the corresponding elements that are shown in the problem:</dt>
			  	<dd>
			  		<?php 
						foreach (Application::$problemCategories as $curProblemCategory) {
					?>
						  		<label><input class="box" type="checkbox" name="categoryArray[]" value="<?= $curProblemCategory?>" /><?= $curProblemCategory?></label><br/>
					<?php 
						}
					?>
			  	</dd>
			  	
				<dt> 3. Write your rationale </dt>			  	
				<textarea id="rationale" rows="20" cols="40"></textarea>		  
			  	<input name="submit" type="button" value="SUBMIT" class="btn" onClick="check(); scroll(0,0);" />
			</dl>
		</form>
	</div> <!-- id="answerSection" -->
</div><!-- id="middle-center"> -->	
<div id="middle-bottom"></div>
 
<label><input type='hidden' id='counter' name='counter' value=""/>

<?php 

require_once './footer.php';
?>