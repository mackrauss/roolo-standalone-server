<?php
session_start();
require_once './header.php';

// check username variable has been sent
if(isset($_GET['username'])){
	$_SESSION['username'] = $_GET['username'];
	$greetingMsg = "Hello " . $_SESSION['username'];
}else {
	$_SESSION['username'] = '';
	$greetingMsg = 'username has not been set !!!';
}
// check this has been taged by teacher or student
if(isset($_GET['masterSolution'])){
	$_SESSION['masterSolution'] = $_GET['masterSolution'];
}else { // zero means false and one means true
	$_SESSION['masterSolution'] = 0;
}

$questions = glob(dirname(__FILE__) . '/../../../Questions/*');
$questions = array_filter($questions, 'is_file');
$counter=0;
for ($i=0; $i<sizeof($questions); $i++){
	$curPath = $questions[$i];
	$curPath = substr($curPath, strrpos($curPath, '/Questions'));
	$questions[$i] = $curPath;
}

?>

<!--<script type='text/javascript' src="/src/js/highslide-full.packed.js"></script>-->
<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>


<script type='text/javascript'>

	// an array that keeps all questions path
	var questions = new Array('<?= implode('\', \'', $questions)?>');

	// an array keeps values all checked check boxes
	var checkedValues = [];

	$(document).ready(function(){

		$('#curQuestion').attr('src', questions[0]);

		$('#greetingDiv').html('<?= $greetingMsg?>');

//		$('.categoryChoice').change(function () {
//		    if($(this).is(':checked')) {
//		        $(this).attr("checked", "checked");
//		        return;
//		    }else {
//		    	$(this).removeAttr("checked");
//		    	return;
//			}
//			
//		});
	});

</script>
<script type='text/javascript'>

	function nextQuestion(){

		//disable the submit button
		$('#submit').attr('disabled', 'disabled');

		var counter = $('#counter').val();

		//gets all checked checkboxes and serializes it
	    checkedValues = $(':checkbox:checked').serialize();

	    //Ajax call to send username, uriOwner, masterSolution, checkedValues
		$.get("/src/php/ajaxServices/tagQuestion.php",
				{author:"<?= $_SESSION['username']?>",
				masterSolution:"<?= $_SESSION['masterSolution']?>",
			 	tags:'[' + checkedValues + ']',
			 	ownerURI:questions[counter]},
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
				
		}
		else{
			$('#imgDiv').html('');
			$('#imgDiv').remove();

			$('#tagQuestionDiv').remove();

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '15%'});
			
			groupingMsg = "<h2 style='width: 100%; float: left'> Please wait for the system to send you to a group</h2>";
			groupingMsg += "<input id='getGroupButton' type='button' value='What is my group' onClick='checkGroup()'/>";
			$('#groupingMsgDiv').html(groupingMsg);
		}
	}

	function checkGroup(){

		$('#groupDiv').hide();
		$('#groupDiv').html('').fadeOut("slow");
		$('#groupDiv').html('<p>The system has not yet calculated which group you belong to. This might be because some peopel are still tagging questions</p>').fadeIn("slow");
	}
		
</script>

<style type='text/css'>

	body {
		font-family: Georgia,"Trebuchet MS",Arial,Helvetica,sans-serif;
		font-weight: normal;
		font-size: 14px; 
		color: #444444;
	}
	
	div#tagQuestionDiv{
		width: 100%;
		height: 100%;
	}

	#imgDiv {
		width: 40%;
		height: 150px;
		float: left;
	}
	
	#categoryDiv {
		width: 40%;
		float: left;
		margin-left: 10%;
	
	}
	
	#greetingDiv {
		width: 100%;
		margin: 2% 0 3% 4%; 
		font-size: 18px;
	}
	
	#getGroupButton {
		width: 200px;
	}
	
	#groupDiv {
		width: 100%;
	}
</style>

<div id='greetingDiv'>

</div>

<div id='groupingMsgDiv'>
</div>

<div id='groupDiv'>
</div>

<div id='tagQuestionDiv'>

	<div id='imgDiv'>
		<img id='curQuestion' src=""/>
	</div>
	
	<div id='categoryDiv'>
		Please choose the category that this question fits best <br/><br/><br/>
			<label><input class='categoryChoice' type="checkbox" name="category" value="Geometry"> Geometry </label><br/> <br/>
			<label><input class='categoryChoice' type="checkbox" name="category" value="Trigonametry"> Trigonametry </label> <br/> <br/>
			<label><input class='categoryChoice' type="checkbox" name="category" value="Exponential"> Exponential </label> <br/> <br/>
			<label><input class='categoryChoice' type="checkbox" name="category" value="Algebra"> Algebra </label> <br/> <br/>
			<label><input type="button" id='submit' value="Submit" onClick="nextQuestion();"/>
			<label><input type='hidden' id='counter' name='counter' value="0"/>
	</div>

</div>



<?php 

require_once './footer.php';









