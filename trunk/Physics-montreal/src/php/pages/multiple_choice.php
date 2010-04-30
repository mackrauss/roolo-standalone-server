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
$noMoreProblemMsg = 'You have finished answering all the questions. Please wait for your teacher to assign you to a super group!';

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

$query = 'type:Problem';
//$allProblems = $rooloClient->search($query, 'metadata', 'latest');
$allProblems = $rooloClient->search($query, 'metadata', 'latest');

$query = "type:Solution AND author:" . $_SESSION['username'];
$authorSolutions = $rooloClient->search($query, 'metadata');
$solutionObject = new Solution();
$problemObject = new Problem();
for($i=0; $i<sizeof($allProblems); $i++){
	$problemObject = $allProblems[$i];
	$uri = $problemObject->uri;
	//echo "</br>Uri[".$i."] = ".$uri;
	$found = FALSE;
	for($j=0; $j<sizeof($authorSolutions); $j++){
		$solutionObject = $authorSolutions[$j];
		$ownerURI = $solutionObject->owneruri;
		//echo "</br>ownerURI[".$j."] = ".$ownerURI;
		if ($ownerURI == $uri){
			$found = TRUE;
			//unset($allQuestions[$j]);
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
//}else{
//	$noMoreProblemMsg = 'You have finished answering all the questions. Please wait for your teacher to assign you to a super group!';
}

$query = 'type:Principle';
$allPrinciples = $rooloClient->search($query, 'metadata', 'latest');
$principlesURIs = array();

for($i=0; $i< sizeof($allPrinciples); $i++){
		$principleObject = $allPrinciples[$i];
		$principlesURIs[$i] = $principleObject->uri;
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
	var rationaleTextMax = 140; //Max length for rationale string in texarea 

	$(document).ready(function(){

		randomIndex = randonCounter();
		$('#counter').val(randomIndex);

		signOutLink = '<a onClick="document.location.href=\'/src/php/ajaxServices/logout.php\'" style="cursor:pointer;"> Sign Out </a>';
		$('#signIn').html('<?= $greetingMsg?>' + " | " + signOutLink);

		if ('<?= $totalResults ?>' == 0){

			$('#middle-top').remove();
			$('#middle-center').remove();
			
			$('#groupingMsgDiv').css({'width' : '70%', 'height' : '18%', 'margin-left':'15%'});
			
			groupingMsg = "<h2 style='width: 100%; float: left; Margin-top: 10%'><?= $noMoreProblemMsg ?></h2>";
			$('#groupingMsgDiv').html(groupingMsg);
			$('#questionHeading').html();
			delay();
		}else{
			
			$('#curQuestion').attr('src', questions[randomIndex]);
			$('#questionHeading').html('Question ' + curQuestionNum + ' of ' + numQuestion);
			$('#timerValue').text( minutes + ":" + seconds );
			$('#charLeftStr').text( rationaleTextMax + " characters left");
			$('#signin').show();
			$('#timer').show();
			countDown(); 
		}

        $('#rationaleTextarea').keyup(function() {
            var len = this.value.length;
            if (len >= rationaleTextMax) {
                this.value = this.value.substring(0, 140);
            }
            var leftChar = (rationaleTextMax - len);
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

	// this function counts the time down from 2:30 to 0:0
	function countDown(){ 
		if (seconds <= 0 || minutes <= -1){
			
			if (seconds <= 0){ 
				    seconds = 59; 
				    minutes -= 1; 
			} 
			if (minutes <= -1){ 
			    seconds = 0; 
			    minutes += 1; 
			    nextQuestion();
			}
		}else{ 
			seconds -= 1;
		} 
		if (seconds < 10)
			$('#timerValue').text( minutes + ":0" + seconds ); 
		else
			$('#timerValue').text( minutes + ":" + seconds ); 
		setTimeout("countDown()",1000); 
	 }

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
				 rationale: rationale,
				 //questionPath: questions[counter],
				 category: category 
				},
		  		function(returned_data){
			  		// We don't need to do anything in the call-back function
			    }
		);
					
		// Delete the showed question and its URI from arraies
		questions.splice(counter,1);
		questionsURI.splice(counter,1);
		
		//changes the question if it is not the last question
		if ( questions.length > 0 ){

			counter = randonCounter();
			
			$('#counter').val(counter);
			$('#curQuestion').attr('src', questions[counter]);

			$('#rationaleTextarea').val('');
			$('#charLeftStr').text( rationaleTextMax + " characters left");

			//uncheck radiobuttons
			$("input[name='choice']:checked").attr("checked", false);

			//uncheck checkBoxes
			$(".box").attr('checked', false);

			$('#submitBtn').removeAttr('disabled');

			seconds = 30; 
			minutes = 2; 
			$('#count').text( minutes + ":" + seconds + "time left"); 

		} else{

			$('#middle-top').remove();
			$('#middle-center').remove();
			$('#signin').remove();
			
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
			$('#questionHeading').html('Question ' + curQuestionNum + ' of ' + numQuestion);
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

</style>

<div id="container2">

<div id="middle-top">
	<div id="questionHeading"><p><b></b></p></div>
	<div id="timer" style='display: none'>
    	<p>TIME REMAINING</p>
    	<h1 id='timerValue'></h1>
  	</div>
	
</div>

<div id='groupingMsgDiv'></div>

<div id="middle-center">
    <div id="questionSection">
    	<img id='curQuestion' src="" width="454" height="320" class="problem" style='border: medium solid #666666'/>
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
			  
			   	<dt>3. Write a rationale explaining why you selected the elements above.</dt>
			  	<dd><textarea id="rationaleTextarea" name="" cols="35" rows="12"></textarea></dd>
				<p id="charLeftStr" ></p>
			  	<input name="submit" type="button" value="SUBMIT" class="btn" onClick="check()" />
			</dl>			  	
		</form>
	</div> <!-- id="answerSection" -->
</div><!-- id="middle-center"> -->	
<div id="middle-bottom"></div>
 
<label><input type='hidden' id='counter' name='counter' value=""/>

<?php 

require_once './footer.php';
?>