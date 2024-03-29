<?php
//session_start();

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';

error_reporting(E_STRICT);
$username = $_SESSION['username'];
$problemUris = $_SESSION['problemUris'];

if(isset($username)){
	$greetingMsg = "Hello " . $username;
}else {
	$username = '';
	$greetingMsg = 'username has not been set !!!';
}

if (!isset($problemUris)){
	//TODO we need to delete these values for the array;
	$problemUris = 'roolo://scy.collide.info/scy-collide-server/5.Problem roolo://scy.collide.info/scy-collide-server/7.Problem roolo://scy.collide.info/scy-collide-server/2.Problem';
	$noProblemsMsg = "There are no problems assigned to your group. Please consult the teacher";
}


// retrieve questions from repository
$rooloClient = new RooloClient();

/**
 * We need to get all the problem URIs from the page in phaseB. Then
 * we can extract all the required info for all questions in this step
 * and we'll continue with running the user through the questions they're
 * assigned to answer.
 */

//TODO the problemUris to be coming from the $_SESSION instead of the request params. But for testing we have this right now
$problemUris = explode(" ", $problemUris);

$problemPathQuery = "type:Problem AND uri:(". implode($rooloClient->escapeSearchTerm($problemUris), " OR ") . ")";

$problemElos = $rooloClient->search($problemPathQuery, 'metadata', 'all');


foreach ($problemElos as $problemElo) {
	echo $problemElo['path']. "<br/>";
//	echo "<img src='" . $problemImage->getPath() . "'/>";	
}

die();

// Loop through all the questions and extract their path on disk
$problemPathQuery = 'type:Problem AND uri:';
foreach ($problemUri as $problemUri) {
	$problemPathQuery .= $problemUri . ' OR ';
}



$totalResults = sizeof($results);

if ($totalResults != 0){
	for ($i=0; $i< sizeof($results); $i++){
		$problemObject = new Problem();
		$problemObject = $results[$i];
		$problems[$i] = $problemObject->path;
		$problemsURI[$i] = $problemObject->uri;
	}
}else{
	$noMoreProblemMsg = 'You have finished answering all the questions. Please wait for your teacher to assign you to a super group!';
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
			$('#countDown').text( "Time Left" + minutes + ":" + seconds );
			$('#charLeftStr').text( rationaleTextMax + " characters left");
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
			$('#countDown').text( "Time Left" + minutes + ":0" + seconds ); 
		else
			$('#countDown').text( "Time Left" + minutes + ":" + seconds ); 
		setTimeout("countDown()",1000); 
	 }

	 function delay (){
		setTimeout ("loginPage()", 10000);
	 }

	 function loginPage(){
		 alert ("Inested of this alert in loginPage function would use the ling to login page");
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
			$('#charLeftStr').text( rationaleTextMax + "characters left");

			$("input[name='choice']:checked").attr("checked", false);

       	    $('#submitBtn').removeAttr('disabled');

			seconds = 30; 
			minutes = 2; 
			$('#count').text( minutes + ":" + seconds + "time left"); 

		} else{
			$('#imgDiv').remove();

			$('#questionDiv').remove();

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});

			groupingMsg = "<h2 style='width: 100%; float: left'> There are no more problems. Please join to your supper group!</h2>";

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
		margin-left: 5%;
		margin-top: 0%;
		
	}
	.title {
		width: 100%;
		height: 15%;
		margin-top: 1%;
		font:13px verdana,sans-serif;
	}

	#choiceDiv {
		width: 100%;
		margin-bottom: 2%; 
		float: left;
	}
	
	#choiceDiv input {
		margin-left: 5%;
	}

	#principleChoiceDiv input {
		margin-top: 1%;
	}

    #charLeftStr {
    	text-align: right;
    	font-size: 12px;
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
		<div style='width: 100%; float: left'>
			<div id='choiceDiv'>
				<h4> Choose your answer </h4>
				<input type="radio" name="choice" value="A">A
				<input type="radio" name="choice" value="B">B
				<input type="radio" name="choice" value="C">C
				<input type="radio" name="choice" value="D">D
				<input type="radio" name="choice" value="E">E
			</div>
		</div>
		
		<div id='rationaleDiv' style='width: 100%; float: left; margin-top: 10%'>
			<div id='textareaTitleDiv' class='title'>
				<div style='float: left; width: 59%'>Provide a rationale for your choice</div>
				<div id="charLeftStr" style='float: right; width: 40%' ></div>
			</div>
			<textarea id='rationaleTextarea' rows='10' cols='50' white-space='nowrap'></textarea>
		</div>
		<div id='submitDiv'>
			<input id='submitBtn' type="button" value='Submit' onClick='nextQuestion()'>
		</div>
	</div>
	<div id='principleChoiceDiv' style='width: 100%; float: left; '>
		<h4> Choose the principle which applies to this question </h4> 
		<input type="radio" name="principleChoice" value="Newton\'s first law">Newton's First Law<br/>
		<input type="radio" name="principleChoice" value="Newton\'s first law">Newton's Second Law<br/>
		<input type="radio" name="principleChoice" value="Newton\'s first law">Newton's Third Law<br/>
	</div>
</div>

<div id='questionSectionDiv'></div>
 
<label><input type='hidden' id='counter' name='counter' value=""/>

<?php 

require_once './footer.php';
