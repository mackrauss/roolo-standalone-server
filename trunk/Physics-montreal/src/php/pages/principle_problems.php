<?php
//session_start();

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';

error_reporting(E_STRICT);

if (!$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
}
$_SESSION['loggedIn'] = FALSE;
$_SESSION['msg'] = "";
$greetingMsg = "Hello " . $_SESSION['username'];

//// check username variable has been sent
//if(isset($_GET['username'])){
//	$_SESSION['username'] = $_GET['username'];
//	$greetingMsg = "Hello " . $_SESSION['username'];
//}else {
//	$_SESSION['username'] = '';
//	$greetingMsg = 'username has not been set !!!';
//}

$noMoreProblemMsg = 'You have finished answering all the questions.';

$rooloClient = new RooloClient();
$query ='';

$query = 'type:Problem';
//
//TODO
// should change the querry like below
//$query = 'type:Problem AND mcmastersolution:null AND principleuri:null';
// it means just retrieve the problem elos that mcmustersolution and principleuri are null
//

$allProblems = $rooloClient->search($query, 'metadata', 'latest');
//$allProblems = $rooloClient->search($query, 'metadata');
//echo "</br>size of all problems = ".sizeof($allProblems);

//$query = "type:Solution AND author:" . $_SESSION['username'];
//$authorSolutions = $rooloClient->search($query, 'metadata');
//$solutionObject = new Solution();
$problemObject = new Problem();
for($i=0; $i<sizeof($allProblems); $i++){
	$problemObject = $allProblems[$i];
	$principleUri = $problemObject->principleuri;
	$mcMasterSolution = $problemObject->mcmastersolution;
	//echo "</br>Uri[".$i."] = ".$uri;
//	$found = FALSE;
	if ($mcMasterSolution != '' && $principleUri != ''){
		unset($allProblems[$i]);
	}
}

$totalResults = sizeof($allProblems);
//echo "</br>size of totalResults = ".sizeof($allProblems);

if ($totalResults != 0){
	for ($i=0; $i< sizeof($allProblems); $i++){
		$problemObject = new Problem();
		$problemObject = $allProblems[$i];
		$problems[$i] = $problemObject->path;
		//echo "</br>parablms PAth = " . $problemObject->path;
		$problemsURIs[$i] = $problemObject->uri;
	}
}

$query = 'type:Principle';
$allPrinciples = $rooloClient->search($query, 'metadata', 'latest');
//echo "</br>size of all principles = ".sizeof($allPrinciples);

for($i=0; $i< sizeof($allPrinciples); $i++){
		$principleObject = new Principle();
		$principleObject = $allPrinciples[$i];
		$principlesPath[$i] = $principleObject->path;
		//echo "</br>principlesPath = ". $principleObject->path;
		$principlesURIs[$i] = $principleObject->uri;
		$principles[$principleObject->path] = $principleObject->uri;
}
?>

<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<script type='text/javascript'>

	// an array that keeps all questions path
	var problems = new Array('<?= implode('\', \'', $problems)?>');
	var problemsURIs = new Array('<?= implode('\', \'', $problemsURIs)?>');

	var principlesPath = new Array('<?= implode('\', \'', $principlesPath)?>');
	var principlesURIs = new Array ('<?= implode('\', \'', $principlesURIs)?>');

	var numProblem = problems.length;
	var curProblemNum = 1;

	$(document).ready(function(){

		$('#curProblem').attr('src', problems[0]);

		$('#greetingDiv').html('<?= $greetingMsg?>');

		$('#curProblemNumDiv').html('<h2> Problem ' + curProblemNum + '/' + numProblem + '</h2>');

		$('div.categoryCount').html('<h3> 0 </h3>');

		$('#groupingMsgDiv').css({'width' : '40%'});

		$('.droppable').corner();

		if ('<?= $totalResults ?>' == 0){

			$('#imgDiv').remove();
			$('#problemDiv').remove();

			$('#greetingDiv').html('<?= $greetingMsg?>');

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});

			groupingMsg = "<h2 style='width: 100%; float: left'>" + '<?= $noMoreProblemMsg ?>' + "</h2>";

			$('#groupingMsgDiv').html(groupingMsg);
			$('#curProblemNumDiv').html('');
			delay();
		}else{
			
			$('#curProblem').attr('src', problems[0]);
			$('#greetingDiv').html('<?= $greetingMsg?>');
			$('#curProblemNumDiv').html('<h2> Problem ' + curProblemNum + '/' + numProblem + '</h2>');
	
			$('div.categoryCount').html('<h3> 0 </h3>');
		}
	});

	function delay (){
			setTimeout ("loginPage()", 5000);
	 }

	 function loginPage(){
		window.location = "/src/php/pages/";
	 } 
</script>

<script type='text/javascript'>

	function nextProblem(chosenPrincipleUri){

		//disable the submit button
		//$('#submit').attr('disabled', 'disabled');

		var counter = $('#counter').val();
		var selectedChoice = $("input[name='choice']:checked").val();

		//Ajax call to send username, problemUri, chosenPrincipleUri, selectedChoice
		$.get("/src/php/ajaxServices/savePrincipleProblemsMasterSolution.php",
			{ username:"<?= $_SESSION['username']?>",
			  masterSolution:selectedChoice,
			  principleUri: chosenPrincipleUri,
			  problemUri:problemsURIs[counter]
			},
	  		function(returned_data){
		  		// We don't need to do anything in the call-back function
			}
		);

		//changes the problem if it is not the last problem
		if ( counter < problems.length - 1 ){
			counter++;
			$('#counter').val(counter);

			$('#curProblem').attr('src', problems[counter]);
			$("input[name='choice']:checked").attr("checked", false);
			$("#laws").val(0);
				
		} else{
			$('#imgDiv').remove();
			$('#problemDiv').remove();
			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});

			groupingMsg = "<h2 style='width: 100%; float: left'>" + '<?= $noMoreProblemMsg ?>' + "</h2>";

			$('#groupingMsgDiv').html(groupingMsg);
			$('#curProblemNumDiv').html('');
			delay();
		}

		// increment the current number of the Problem
		curProblemNum++;
		if (curProblemNum <= numProblem) {
			$('#curProblemNumDiv').html('<h2> Problem ' + curProblemNum + '/' + numProblem + '</h2>');
		}
	}

	function submit(){
		
		// Get the principle's uri
		chosenPrincipleUri = $('#laws').find(':selected').attr('principleUri');

 	 	//** is Multiple choice MasterSolution answer has selected
	 	sc = $("input[name:'choice']:checked").val();
	 	if ( sc == "A" || sc == "B" || sc == "C" || sc == "D" ){
			$('#mcTitleDiv').css('color','black');
		 	nextProblem(chosenPrincipleUri);
		}else{
			//alert ("select A B C or D");
			$('#mcTitleDiv').css('color','red');
		}	 
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
		margin: 2% 1% 0 2%; 
		font-size: 20px;
		float: left;
	}
	
	#curProblemNumDiv {
		width: 100%;
		text-align: left; 
		margin-left: 2%;
	}
	
	#leftDiv {
		width: 40%;
		float: left;
		margin-left: 3%;
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

	.titleDiv {
		width: 100%;
		height: 15%;
		margin-top: 1%;
		font:13px verdana,sans-serif;
	}		

	#choiceDiv {
		width: 80%;
		margin-left: 10%;
		margin-bottom: 7%; 
	}
	
	#principleDiv{
		width: 80%;
		margin-left: 5%;
		margin-top: 5%; 
	}
	
	
	#principleTable td{
		padding: 2px;
		border: 1px solid #333333;
	}

	#submitDiv{
		width: 100%;
		margin: 4% 0% 0% 70%;
	}

	.droppable-hover {
	     border: 1px solid #669900;
	}
</style>

<div id='greetingDiv'></div>

<div id='curProblemNumDiv'></div>

<div id='groupingMsgDiv'></div>

<div id='groupDiv'></div>

<div id='problemDiv'>
	<div id='leftDiv'>
		 <div id='imgDiv'>
			<img height="350px" class='problem' id='curProblem' src=""  class='droppable'/>
	 	</div>
	</div>
	<div id='answerDiv'>
		<div id='mcTitleDiv' class='titleDiv'>
			<font size='3px'> 1_ Select the correct answer! </font><br/><br/>
		</div>
		<div id='choiceDiv'>
			<input type="radio" name="choice" value="A"><b>A</b><br>
			<input type="radio" name="choice" value="B"><b>B</b><br>
			<input type="radio" name="choice" value="C"><b>C</b><br>
			<input type="radio" name="choice" value="D"><b>D</b><br>
		</div>
		<div id='principleTitleDiv' class='titleDiv'>
			<font size="3px"> 2_ Select the principle for the given problem! </font><br/><br/>
		</div>
		<div id='principleDiv'>
		    <select name="laws" id="laws">
	        	<option value="law1" principleUri='<?= $principlesURIs[0]?>'>Newton's First Law</option>
	        	<option value="law2" principleUri='<?= $principlesURIs[1]?>'>Newton's second Law</option>
	        	<option value="law3" principleUri='<?= $principlesURIs[2]?>'>Newton's third Law</option>
    		</select>
		</div>
		<div id='submitDiv'>
			<input class='btn' type="button" value='Submit' onClick='submit();'>
		</div>
		
	</div>
 </div>
 
 <label><input type='hidden' id='counter' name='counter' value="0"/>
<?php 

require_once './footer.php';









