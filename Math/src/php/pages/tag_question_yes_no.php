<?php
//session_start();

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/QuestionCategory.php';
require_once '../dataModels/Question.php';

error_reporting(E_STRICT);
// check username variable has been sent
if(isset($_GET['username'])){
	$_SESSION['username'] = $_GET['username'];
	$greetingMsg = "Hello " . $_SESSION['username'];
}else {
	$_SESSION['username'] = '';
	$greetingMsg = 'username has not been set !!!';
}
// check role variable has been sent
if(isset($_GET['role'])){
	$_SESSION['role'] = $_GET['role'];
}else {
	$_SESSION['role'] = 'student';
	//$greetingMsg = 'role has not been set !!!';
}

// retrieve questions from repository
$rooloClient = new RooloClient();
$query ='';
$results = array();

if ($_SESSION['role'] == 'teacher'){
	$query = 'type:Question AND masterSolution:"null"';
	$results = $rooloClient->search($query, 'metadata', 'latest');
}else {
	$query = 'type:Question';
	$allQuestions = $rooloClient->search($query, 'metadata', 'latest');

	$query = "type:QuestionCategory AND author:" . $_SESSION['username'];
	$tagedQuestions = $rooloClient->search($query, 'metadata');

	$questionCategoryObject = new QuestionCategory();
	$questionObject = new Question();
	for($i=0; $i<sizeof($allQuestions); $i++){
		$questionObject = $allQuestions[$i];
		$uri = $questionObject->get_uri();
		//echo "</br>ownerUri[".$i."] = ".$ownerURI;
		$found = FALSE;
		for($j=0; $j<sizeof($tagedQuestions); $j++){
			$questionCategoryObject = $tagedQuestions[$j];
			$ownerURI = $questionCategoryObject->get_ownerUri();
			//echo "</br>uri[".$j."] = ".$uri;
			if ($ownerURI == $uri){
				$found = TRUE;
				//unset($allQuestions[$j]);
			}
		}
		if (!$found){
			array_push($results, $allQuestions[$i]);
		}
	}
}
$totalResults = sizeof($results);

if ($totalResults != 0){
	for ($i=0; $i< sizeof($results); $i++){
		$questionObject = new Question();
		$questionObject = $results[$i];
		$questions[$i] = $questionObject->get_path();
		$questionsURI[$i] = $questionObject->get_uri();
		
	}
}else{
	$noQuestionMsg = 'All questions have been taged. There are no more questions to be taged!';
}

?>

<!--<script type='text/javascript' src="/src/js/highslide-full.packed.js"></script>-->
<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>


<script type='text/javascript'>

	// an array that keeps all questions path
	var questions = new Array('<?= implode('\', \'', $questions)?>');

	var questionsURI = new Array('<?= implode('\', \'', $questionsURI)?>');

	var numQuestion = questions.length;
	var curQuestionNum = 1;

	// an array keeps values all checked check boxes
	var checkedValues = [];

	$(document).ready(function(){

		if ('<?= $totalResults ?>' == 0){

			$('#imgDiv').remove();
			$('#tagQuestionDiv').remove();

			$('#greetingDiv').html('<?= $greetingMsg?>');

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});

			if ('<?= $_SESSION['role']?>' == 'teacher'){
				groupingMsg = "<h2 style='width: 100%; float: left'>" + '<?= $noQuestionMsg ?>' + "</h2>";
			}else{
				groupingMsg = "<h2 style='width: 100%; float: left'> Please wait for the system to send you to a group</h2>";
				groupingMsg += "<input id='getGroupButton' type='button' value='What is my group' onClick='checkGroup()'/>";
			}	
			$('#groupingMsgDiv').html(groupingMsg);
			$('#curQuestionNumDiv').html('');
		}else{

			$('#signout').show();
			$('#curQuestion').attr('src', questions[0]);
			$('#greetingDiv').html('<?= $greetingMsg?>');
			$('#curQuestionNumDiv').html('<h2> Question ' + curQuestionNum + '/' + numQuestion + '</h2>');
	
			$('div.categoryCount').html('<h3> 0 </h3>');
			$('.droppable').corner();
		}
		
		// DRAG and DROP functionality
		$(".draggable").draggable({

//			helper: 'original',
//			revertDuration: 1000,
//			snap: '.droppable',

			revert: 'invalid',
			opacity: '0.80',
			cursor: 'move',
			cursorAt: { top: 0, left: 0 },
			helper: function(event) {
				return $('<div style="background-color: #333333; color: white; border: 1px solid white; width: 100px; height: 20px; text-align: center"> Question ' + curQuestionNum + '</div>');
			}
		});
		

		$(".droppable").droppable({

			tolerance: 'touch',
			accept: '.draggable',
			hoverClass: 'droppable-hover',
			drop: function(ev, ui) {

				 category = $(this).find('h4').html();
				 categoryCount = $(this).find('h3').html();
				 categoryCount ++;
			     $(this).html("<h4>" + category + "</h4>" + "<div class='categoryCount'><h3>" + categoryCount + "</h3></div>").fadeIn('slow');


			     // **** parameter should be taken out if we don't want drag and drop
			     nextQuestion(category);

			     //Do your AJAX stuff in here.
			}
		});
	});

</script>
<script type='text/javascript'>

	function nextQuestion(category){

		//disable the submit button
		$('#submit').attr('disabled', 'disabled');

		var counter = $('#counter').val();

		//gets all checked checkboxes and serializes it
	    checkedValues = $(':checkbox:checked').serialize();
	    checkedValues = $('.categoryChoice:checked').val();
//		alert('uri =' + questionsURI[counter]);
	    //Ajax call to send username, uriOwner, masterSolution, checkedValues
		$.get("/src/php/ajaxServices/tagQuestionYesNo.php",
				{author:"<?= $_SESSION['username']?>",
				role:"<?= $_SESSION['role']?>",
				masterSolution:"<?= $_SESSION['masterSolution']?>",
				path:questions[counter],
				ownerURI:questionsURI[counter],
				answer:category},
		  		function(returned_data){
			  		// We don't need to do anything in the call-back function
			    }
		);
					
		$('.categoryChoice').attr('checked', false);

		//changes the question if it is not the last question
		if ( counter < questions.length - 1 ){
			counter++;
			$('#counter').val(counter);
			$('#curQuestion').attr('src', questions[counter]);

			//enable the submit button
			$('#submit').removeAttr('disabled');
				
		} else{
			$('#imgDiv').html('');
			$('#imgDiv').remove();

			$('#tagQuestionDiv').remove();

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});

			if ('<?= $_SESSION['role']?>' == 'teacher'){
				groupingMsg = "<h2 style='width: 100%; float: left'> All questions have been taged. There are no more questions to be taged! </h2>";
			}else{
				groupingMsg = "<h2 style='width: 100%; float: left'> Please wait for the system to send you to a group</h2>";
				groupingMsg += "<input id='getGroupButton' type='button' value='What is my group' onClick='checkGroup()'/>";
			}	
			$('#groupingMsgDiv').html(groupingMsg);
			$('#curQuestionNumDiv').html('');
		}

		// increment the current number of the question
		curQuestionNum++;
		if (curQuestionNum <= numQuestion) {
			$('#curQuestionNumDiv').html('<h2> Question ' + curQuestionNum + '/' + numQuestion + '</h2>');
		}
	}

	function checkGroup(){

		$('#groupDiv').css({'height' : '10%'});
		$('#groupDiv').hide();
		$('#groupDiv').html('').fadeOut("slow");
		$('#groupDiv').html('<p>The system has not yet calculated which group you belong to. This might be because some people are still tagging questions</p>').fadeIn("slow");
	}
		
</script>

<style type='text/css'>

	body{
		font-family: Georgia,"Trebuchet MS",Arial,Helvetica,sans-serif;
		font-weight : normal;
		font-size: 14px; 
		color:#444444;
	}
	
	div#tagQuestionDiv{
		width: 100%;
		height: 100%;
	}

	#imgDiv {
		width: 40%;
		height: 170px;
		float: left;
	}
	
	#categoryDiv {
		width: 40%;
		float: left;
		margin-left: 5%;
	
	}
	
	#greetingDiv {
		width: 100%;
		margin: 2% 0 0 4%; 
		font-size: 18px;
	}
	
	#getGroupButton {
		width: 200px;
	}
	
	#groupDiv {
		width: 100%;
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
	     background-color: #669900;
	}
	
</style>

<div id='greetingDiv'>

</div>

<div id='groupingMsgDiv'>
</div>

<div id='groupDiv'>
</div>


<div id='curQuestionNumDiv' style='width: 100%; text-align: left; margin-left: 3%;'>
	
</div>



<div id='tagQuestionDiv'>

	<div id='imgDiv' >
		<img id='curQuestion' src="" class='draggable'/>
	</div>
	
	<div id='categoryDiv'>
			<font size="3px"> Does this question belong to your category? </font><br/><br/>
		
			<div class='categoryDiv categoryDivLeft droppable'>
				<h4>YES</h4> 
				<div id='yesCount' class='categoryCount'> </div>
			</div>
			
			<div class='categoryDiv categoryDivRight droppable'>
				<h4>NO</h4> 
				<div id='noCount' class='categoryCount'> </div>
			</div>
			<label><input type='hidden' id='counter' name='counter' value="0"/>
	</div>

</div>


<?php 

require_once './footer.php';









