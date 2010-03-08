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
$greetingMsg = "Hello " . $_SESSION['username'];
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
$allProblems = $rooloClient->search($query, 'metadata');

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

if ($totalResults != 0){
	for ($i=0; $i< sizeof($results); $i++){
		$problemObject = new Problem();
		$problemObject = $results[$i];
		$problems[$i] = $problemObject->path;
		$problemsURI[$i] = $problemObject->uri;
	}
//}else{
//	$noMoreProblemMsg = 'You have finished answering all the questions. Please wait for your teacher to assign you to a super group!';
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

		$('#greetingDiv').html('<?= $greetingMsg?>');

		if ('<?= $totalResults ?>' == 0){

			$('#imgDiv').remove();
			$('#questionDiv').remove();

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});

			groupingMsg = "<h2 style='width: 100%; float: left'> '<?= $noMoreProblemMsg ?>'</h2>";
			$('#groupingMsgDiv').html(groupingMsg);
			$('#curQuestionNumDiv').html('');
			delay();
		}else{
			
			$('#curQuestion').attr('src', questions[randomIndex]);
			$('#curQuestionNumDiv').html('<h2> Question ' + curQuestionNum + '/' + numQuestion + '</h2>');
			$('#countDown').text( "Time Left " + minutes + ":" + seconds );
			$('#charLeftStr').text( rationaleTextMax + " characters left");
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
			$('#countDown').text( "Time Left " + minutes + ":0" + seconds ); 
		else
			$('#countDown').text( "Time Left " + minutes + ":" + seconds ); 
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

	    //Ajax call to send group, selectedChoice, Owneruri, rationale
		$.get("/src/php/ajaxServices/saveMultiplechoice.php",
				{username:"<?= $_SESSION['username']?>",
				 choice: selectedChoice,
				 ownerURI:questionsURI[counter],
				 rationale: rationale
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
			$('#charLeftStr').text( rationaleTextMax + " characters left");

			$("input[name='choice']:checked").attr("checked", false);

       	    $('#submitBtn').removeAttr('disabled');

			seconds = 30; 
			minutes = 2; 
			$('#count').text( minutes + ":" + seconds + "time left"); 

		} else{
			$('#imgDiv').remove();

			$('#questionDiv').remove();

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});

			groupingMsg = "<h2 style='width: 100%; float: left'> '<?= $noMoreProblemMsg ?>' </h2>";

			$('#groupingMsgDiv').html(groupingMsg);
			$('#curQuestionNumDiv').html('');
			delay();
		}

		// increment the current number of the question
		curQuestionNum++;
		if (curQuestionNum <= numQuestion) {
			$('#curQuestionNumDiv').html('<h2> Question ' + curQuestionNum + '/' + numQuestion + '</h2>');
		}
	}

	//function generate the counter value (the index of curent question in 
	//questions and questionsURI arraies)
	function randonCounter(){
		adjustedHigh = questions.length;
        return Math.floor(Math.random()*adjustedHigh);
	}		
	
</script>

<style type='text/css'>

	body {
		font-family: Georgia,"Trebuchet MS",Arial,Helvetica,sans-serif;
		font-weight: normal;
		font-size: 14px; 
		color: #444444;
	}
	
	#greetingDiv {
		width: 100%;
		margin: 2% 0 0 1%; 
		font-size: 20px;
		float: left;
	}
	
	#curQuestionNumDiv {
		width: 100%;
		text-align: left; 
		margin-left: 1%;
	}

	#questionDiv {
		width: 100%;
		float: left;
		margin-left: 1%;
		margin-top: 2%;
	
	}
	#countDown {
		width: 100%;
		float: left;
		margin-left: 30%;
		margin-top: 0%;
	}	
	#imgDiv {
		width: 40%;
		height: 170px;
		float: left;
	}
	
	#answerDiv {
		width: 40%;
		height: 170px;
		float: left;
		margin-left: 10%;
		margin-top: 0%;
		
	}
	.title {
		width: 100%;
		height: 15%;
		margin-top: 1%;
		font:13px verdana,sans-serif;
	}

	#choiceDiv {
		width: 80%;
		margin-left: 3%;
		margin-bottom: 7%; 
	}

    #charLeftStr {
    	color:	#736F6E;
    	text-align: right;
    	//font-style: italic;
    	font-weight: bold;
    	font-size: 10px;
    	font-family:"Lucida Console", Lucida Console, serif;
    	margin-left = 2%;
    }
	#submitDiv {
	 	float: left;
	 	margin-left: 74%;
	 	width: 20%; 
	}
	
	#submitBtn{
		margin-top: 10%;
		margin-left: 20%
	}
</style>

<div id='greetingDiv'></div>

<div id='groupingMsgDiv'></div>

<div id='groupDiv'></div>

<div id='curQuestionNumDiv'></div>


<div id='questionDiv'>
	<div id="countDown">
	</div>
	<div id='imgDiv'>
		<img id='curQuestion' src="" />
	</div>
	<div id='answerDiv'>
		<div id='moltiplecchoiceTitleDiv' class='title'>
			<font size='3px'> Select Correct answer </font><br/><br/>
		</div>
		<div id='choiceDiv'>
			<input type="radio" name="choice" value="A"><b>A</b><br>
			<input type="radio" name="choice" value="B"><b>B</b><br>
			<input type="radio" name="choice" value="C"><b>C</b><br>
			<input type="radio" name="choice" value="D"><b>D</b><br>
<!--			<input type="radio" name="choice" value="E"><b>E</b><br>-->
		</div>
		<div id='rationaleDiv'>
			<div id='textareaTitleDiv' class='title'>
				<font size='3px'>Add Rationale</font>
				&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				<span id="charLeftStr" ></span>
			</div>
			<textarea id='rationaleTextarea' rows='5' cols='50'	white-space='nowrap'></textarea>
		</div>
		<div id='submitDiv'>
			<input id='submitBtn' type="button" value='Submit' onClick='nextQuestion()'>
		</div>
	</div>
</div>

<div id='questionSectionDiv'></div>
 
<label><input type='hidden' id='counter' name='counter' value=""/>

<?php 

require_once './footer.php';
