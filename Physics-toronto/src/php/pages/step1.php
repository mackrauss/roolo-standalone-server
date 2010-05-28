<?php

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';

error_reporting(E_STRICT);
if (!$_SESSION['loggedIn'])
	header("Location:/src/php/pages/");
$_SESSION['loggedIn'] = FALSE;	
$_SESSION['msg'] = "";
$greetingMsg = "Signed in as <b> " . $_SESSION['username'] . "</b>";
$noMoreProblemMsg1 = 'Great job completing step 1!';
$noMoreProblemMsg2 = 'Please sign out then log in again with your group ID.';

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
$query = 'type:Problem';
$allProblems = $rooloClient->search($query, 'metadata', 'latest');

//Determine all questions object without solutions
$query = "type:Solution AND author:" . $_SESSION['username'];
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

		randomIndex = randonCounter();
		$('#counter').val(randomIndex);

		signOutLink = '<a onClick="document.location.href=\'/src/php/ajaxServices/logout.php\'" style="cursor:pointer;"> Sign Out </a>';
		$('#signIn').html('<?= $greetingMsg?>' + " | " + signOutLink);

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
			$('#signin').show();
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
		window.location = "/src/php/pages/";
	 } 
</script>
<script type='text/javascript'>

	function nextQuestion(){

		//disable the submit button
		$('#submitBtn').attr('disabled', 'disabled');

		var counter = $('#counter').val();
		var rationale = $('#rationaleTextarea').val();
		var selectedChoice = $("input[name='choice']:checked").val();
		var selectedCategory = new Array();
		$("input[name='categoryArray[]']:checked").each(function() {selectedCategory.push($(this).val());});
		var category = selectedCategory.join(', ');

		//Ajax call to send group, selectedChoice, Owneruri, rationale
		$.get("/src/php/ajaxServices/saveMultiplechoice.php",
				{username:"<?= $_SESSION['username']?>",
				 choice: selectedChoice,
				 ownerURI:questionsURI[counter],
				 flag: flag,
				 category: category
				},
		  		function(returned_data){
			  		// We don't need to do anything in the call-back function
			    }
		);
					
		// Delete the showed question and its URI from arraies
		questions.splice(counter,1);
		questionsURI.splice(counter,1);
		flag = "false";
		
		//changes the question if it is not the last question
		if ( questions.length > 0 ){

			counter = randonCounter();
			
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

			$('#middle-top').remove();
			$('#middle-center').remove();
			$('#middle-bottom').remove();
			$('#signin').remove();
			
			$('#groupingMsgDiv').css({'width' : '70%', 'height' : '18%', 'margin-left':'15%'});

			groupingMsg = "<h2 style='width: 100%; float: center; Margin-top: 10%'><?= $noMoreProblemMsg1 ?></h2>";
			groupingMsg += "<h2 style='width: 100%; float: center'><?= $noMoreProblemMsg2 ?></h2>";

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

	//function generate the counter value (the index of curent question in 
	//questions and questionsURI arraies)
	function randonCounter(){
		adjustedHigh = questions.length;
        return Math.floor(Math.random()*adjustedHigh);
	}

	function check(){
		var choice = $("input[name='choice']:checked").val();
		if (choice == 'A' || choice == 'B' || choice == 'C' || choice == 'D' || choice == 'E'){
			flag = "true";
			nextQuestion();
		}else{	
			alert ("Please select the corect answer!");
		}
	}		
	
</script>

<style tyle='text/css'>
	div#signIn {
		position:absolute;
		right:16px;
		font-family:Arial, Helvetica, sans-serif;
		color:#CCC;
		padding-top: 20px;
	} 
	
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
    	<img id='curQuestion' src="" width="454" height="320" class="problem" style='border: none'/>
  	</div>
	<div id="answerSection">
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