<?php
//session_start();

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';

error_reporting(E_STRICT);
// check username variable has been sent
if(isset($_GET['username'])){
	$_SESSION['username'] = $_GET['username'];
	$greetingMsg = "Hello " . $_SESSION['username'];
}else {
	$_SESSION['username'] = '';
	$greetingMsg = 'username has not been set !!!';
}
//// check role variable has been sent
//if(isset($_GET['role'])){
//	$_SESSION['role'] = $_GET['role'];
//}else {
//	$_SESSION['role'] = 'student';
//	//$greetingMsg = 'role has not been set !!!';
//}

// retrieve questions from repository
$rooloClient = new RooloClient();
$query ='';
$results = array();

//if ($_SESSION['role'] == 'teacher'){
	//$query = 'type:Problems AND principleuri:""';
	$query = 'type:Problem';
	$results = $rooloClient->search($query, 'metadata', 'latest');
//}else {
//	$query = 'type:Question';
//	$allQuestions = $rooloClient->search($query, 'metadata', 'latest');
//
//	$query = "type:QuestionCategory AND author:" . $_SESSION['username'];
//	$tagedQuestions = $rooloClient->search($query, 'metadata');
//
//	$questionCategoryObject = new QuestionCategory();
//	$problemObject = new Question();
//	for($i=0; $i<sizeof($allQuestions); $i++){
//		$problemObject = $allQuestions[$i];
//		$uri = $problemObject->get_uri();
//		//echo "</br>ownerUri[".$i."] = ".$ownerURI;
//		$found = FALSE;
//		for($j=0; $j<sizeof($tagedQuestions); $j++){
//			$questionCategoryObject = $tagedQuestions[$j];
//			$ownerURI = $questionCategoryObject->get_ownerUri();
//			//echo "</br>uri[".$j."] = ".$uri;
//			if ($ownerURI == $uri){
//				$found = TRUE;
//				//unset($allQuestions[$j]);
//			}
//		}
//		if (!$found){
//			array_push($results, $allQuestions[$i]);
//		}
//	}
//}
$totalResults = sizeof($results);
//echo "totalResult = ".$totalResults;

if ($totalResults != 0){
	for ($i=0; $i< sizeof($results); $i++){
		$problemObject = new Problem();
		$problemObject = $results[$i];
		$problems[$i] = $problemObject->path;
		$problemsURIs[$i] = $problemObject->uri;
	}
}else{
	$noQuestionMsg = 'All problems have owned thire principle. There are no more problems to assign principle!';
}


//grabbing all the formulas from disk
$priciples = glob(dirname(__FILE__) . '/../../../Principles/*');
$priciples = array_filter($priciples, 'is_file');
$principleCounter=0;
for ($i=0; $i<sizeof($priciples); $i++){
	$curPath = $priciples[$i];
	$curPath = substr($curPath, strrpos($curPath, '/Principles'));
	$priciples[$i] = $curPath;
}
?>

<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<script type='text/javascript'>

	// an array that keeps all questions path
	var problems = new Array('<?= implode('\', \'', $problems)?>');

	var problemsURIs = new Array('<?= implode('\', \'', $problemsURIs)?>');

	var numProblem = problems.length;
	var curProblemNum = 1;

	// an array keeps values all checked check boxes
//	var checkedValues = [];

	$(document).ready(function(){

//		$('#principleTable td:first').css({'padding-right' : '20px'});

		$('#curProblem').attr('src', problems[0]);

		$('#greetingDiv').html('<?= $greetingMsg?>');

		$('#curProblemNumDiv').html('<h2> Problem ' + curProblemNum + '/' + numProblem + '</h2>');

		$('div.categoryCount').html('<h3> 0 </h3>');

		$('#groupingMsgDiv').css({'width' : '40%'});

		$('.droppable').corner();

		if ('<?= $totalResults ?>' == 0){

			$('#imgDiv').remove();
			$('#tagProblemDiv').remove();
			$('#categoryDiv').remove();

			$('#greetingDiv').html('<?= $greetingMsg?>');

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});

//			if ('<?= $_SESSION['role']?>' == 'teacher'){
				groupingMsg = "<h2 style='width: 100%; float: left'>" + '<?= $noQuestionMsg ?>' + "</h2>";
//			}else{
//				groupingMsg = "<h2 style='width: 100%; float: left'> Please wait for the system to send you to a group</h2>";
//				groupingMsg += "<input id='getGroupButton' type='button' value='What is my group' onClick='checkGroup()'/>";
//			}	
			$('#groupingMsgDiv').html(groupingMsg);
			$('#curProblemNumDiv').html('');
		}else{
			
			$('#curProblem').attr('src', problems[0]);
			$('#greetingDiv').html('<?= $greetingMsg?>');
			$('#curProblemNumDiv').html('<h2> Problem ' + curProblemNum + '/' + numProblem + '</h2>');
	
			$('div.categoryCount').html('<h3> 0 </h3>');
			$('.droppable').corner();
		}

		$('.droppable').corner();

		// DRAG and DROP functionality
		$(".draggable").draggable({

//			helper: 'original',
//			revertDuration: 1000,
//			snap: '.droppable',

			revert: 'invalid',
			opacity: '0.80',
			cursor: 'move',
			cursorAt: { top: 0, left: 0 },
			helper: 'clone'
//			helper: function(event) {
//				return $('<div style="background-color: #333333; color: white; border: 1px solid white; width: 100px; height: 20px; text-align: center"> Formula ' + curProblemNum + '</div>');
//			}
		});
		

		$(".droppable").droppable({

			tolerance: 'touch',
			accept: '.draggable',
			hoverClass: 'droppable-hover',
			drop: function(ev, ui) {

//				 category = $(this).find('h4').html();
//				 categoryCount = $(this).find('h3').html();
//				 categoryCount ++;
//			     $(this).html("<h4>" + category + "</h4>" + "<div class='categoryCount'><h3>" + categoryCount + "</h3></div>").fadeIn('slow');

				 // Get the formula's path on disk
			 	 chosenPrinciplePath = $(ui.draggable).find('img').attr('src');
			
			     // **** parameter should be taken out if we don't want drag and drop
//			     nextProblem(category);
				 nextProblem(chosenPrinciplePath);

			     //Do your AJAX stuff in here.
			}
		});
	});

</script>
<script type='text/javascript'>

	function nextProblem(chosenPrinciplePath){

		//disable the submit button
		$('#submit').attr('disabled', 'disabled');

		var counter = $('#counter').val();

		//gets all checked checkboxes and serializes it
//	    checkedValues = $(':checkbox:checked').serialize();
//	    checkedValues = $('.categoryChoice:checked').val();

	    //Ajax call to send username, uriOwner, masterSolution, checkedValues
		$.get("/src/php/ajaxServices/postPrincipleToProblem.php",
				{author:"<?= $_SESSION['username']?>",
				masterSolution:"<?= $_SESSION['masterSolution']?>",
				formulaURI: chosenPrinciplePath,
				ownerURI:problemsURIs[counter],
			 	role:"<?= $_SESSION['role']?>"},
		  		function(returned_data){
			  		// We don't need to do anything in the call-back function
			    }
		);

		// This is to show a checkMark icon to show that the formula is accepted
//		$('#ProblemSectionDiv').append('<img id="checkMark" width="60px" height="60px" src="/resources/check-mark.jpg" style="margin-top: 0px; float: left"/>');
//		$('#problemSectionDiv img#checkMark').fadeIn(8000);
//		$('#problemSectionDiv img#checkMark').fadeOut(2000);
//		$('#problemSectionDiv').remove('img#checkMark');
					
		$('.categoryChoice').attr('checked', false);

		//changes the problem if it is not the last problem
		if ( counter < problems.length - 1 ){
			counter++;
			$('#counter').val(counter);
			$('#curproblem').attr('src', problems[counter]);
				
		} else{
			$('#imgDiv').html('');
			$('#imgDiv').remove();

			$('#categoryDiv').html('');
			$('#categoryDiv').remove();

			$('#tagproblemDiv').remove();

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});

			if ('<?= $_SESSION['role']?>' == 'teacher'){
				groupingMsg = "<h2 style='width: 100%; float: left'> All problems have been taged. There are no more problems to be taged! </h2>";
			}else{
				groupingMsg = "<h2 style='width: 100%; float: left'> Please wait for the system to send you to a group</h2>";
				groupingMsg += "<input id='getGroupButton' type='button' value='What is my group' onClick='checkGroup()'/>";
			}	
			$('#groupingMsgDiv').html(groupingMsg);
			$('#curProblemNumDiv').html('');
		}

		// increment the current number of the Problem
		curProblemNum++;
		if (curProblemNum <= numProblem) {
			$('#curProblemNumDiv').html('<h2> Problem ' + curProblemNum + '/' + numProblem + '</h2>');
		}
	}

	function checkGroup(){

		$('#groupDiv').css({'height' : '10%'});
		$('#groupDiv').hide();
		$('#groupDiv').html('').fadeOut("slow");
		$('#groupDiv').html('<p>The system has not yet calculated which group you belong to. This might be because some people are still tagging problems</p>').fadeIn("slow");
	}
		
</script>

<style type='text/css'>

	body {
		font-family: Georgia,"Trebuchet MS",Arial,Helvetica,sans-serif;
		font-weight: normal;
		font-size: 14px; 
		color: #444444;
	}
	
	#imgDiv {
		width: 40%;
		height: 170px;
		float: left;
	}
	
	div#tagProblemDiv{
		width: 100%;
		height: 100%;
	}

	#problemSectionDiv {
		width: 40%;
		float: left;
		margin-left: 100px;
	}
	
	#categoryDiv {
		width: 40%;
		float: left;
		margin-left: 1%;
		margin-top: 30px;
	
	}
	
	#greetingDiv {
		width: 50%;
		margin: 2% 0 0 1%; 
		font-size: 20px;
		float: left;
	}
	
	#getGroupButton {
		width: 200px;
	}
	
	#groupDiv {
		width: 40%;
		margin-top: 2%;
	}
	
	.categoryDiv {
		
		font-family: Georgia,"Trebuchet MS",Arial,Helvetica,sans-serif;
		font-weight: normal;
		font-size: 20px; 
		color: #444444;	
	
		border: 1px solid #6699CC;
		background-color: #6699CC;
		color: white;
		width: 39%;
		margin-bottom: 10%;
		height: 110px;
		text-align: center;
		vertical-align: middle;
		float: left;
	}
	
	
	.categoryDivLeft {
		margin-right: 10%;
	}
	
	.categoryDivRight {
		margin-left: 10%;
	}
	
	.categoryDiv h4{
		margin-bottom: 0;
	}
	
	.categoryCount {
		margin-top: 0;
	}
	
	
	.droppable-hover {
	     border: 1px solid #669900;
	}
	
	#principleTable td{
		padding: 10px;
		border: 1px solid #333333;
	}
	
	
</style>

<div id='greetingDiv'>

</div>

<div id='curProblemNumDiv' style='text-align: left; float: right; margin-right: 40px; width: 43%'>
		
		</div>


<div id='groupingMsgDiv'>
</div>

<div id='groupDiv'>
</div>



<!--<div id='tagQuestionDiv'>-->

	<div id='categoryDiv'>
			<font size="3px"> Drag the formula you need to use to solve the given problem, onto the problem </font><br/><br/>
			
			<table id='principleTable'>
				<tbody>

					<tr>				
					<?php 
						for ($i=0; $i < sizeof($priciples); $i++){
							if ($i % 2 == 0 && $i != 0){
								
							?>
								</tr>
								<tr>
							<?php 
							}
							?>	
								<td class='draggable'> <img width="200px" height="50px" src='<?= $priciples[$i]?>'/> </td>
							<?php 
						}
					
					?>
					</tr>
				</tbody>
			</table>
	 </div>
	 
	 <div id='problemSectionDiv'>
		 
		 
		 <div id='imgDiv'>
			<img id='curProblem' src=""  class='droppable'/>
		 </div>
	 </div>
	 
	 <label><input type='hidden' id='counter' name='counter' value="0"/>
	 
		
<!--			<div id='geometricCatDiv' class='categoryDiv categoryDivLeft droppable'>-->
<!--				<h4> Geometry </h4> -->
<!--				<div id='geometryCount' class='categoryCount'> </div>-->
<!--			</div>-->
<!--			-->
<!--			<div id='exponentialCatDiv' class='categoryDiv categoryDivRight droppable'>-->
<!--				<h4> Exponential </h4> -->
<!--				<div id='exponentialCount' class='categoryCount'> </div>-->
<!--			</div>-->
<!--			-->
<!--			<div id='trigonametricCatDiv' class='categoryDiv categoryDivLeft droppable'>-->
<!--				<h4> Trigonometry </h4> -->
<!--				<div id='trigonametricCount' class='categoryCount'> </div>-->
<!--			</div>-->
<!--			-->
<!--			<div id='algebraCatDiv' class='categoryDiv categoryDivRight droppable'>-->
<!--				<h4> Algebra </h4> -->
<!--				<div id='algebraCount' class='categoryCount'> </div>-->
<!--			</div>-->
<!--			<label><input type='hidden' id='counter' name='counter' value="0"/>-->
		
<!--</div>-->


<?php 

require_once './footer.php';









