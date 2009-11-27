<?php
require_once './header.php';

// check username variable has been sent
if(isset($_GET['username'])){
	$_SESSION['username'] = $_GET['username'];
}else {
	echo 'username has not been set!!!!!!!!!!!!!';
}
// check this has been taged by teacher or student
if(isset($_GET['masterSolution'])){
	$_SESSION['masterSolution'] = $_GET['masterSolution'];
}else { // zero means false and one means true
	$_SESSION['masterSolution'] = 0;
}

echo "<h2>" . $_SESSION['username'] . "</h2>";

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

		$('.categoryChoice').change(function () {
		    if($(this).is(':checked')) {
		        $(this).attr("checked", "checked");
		        return;
		    }else {
		    	$(this).removeAttr("checked");
		    	return;
			}
			
		});
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

		$.get("receive_variables.php",
				{author:"<?= $_SESSION['username']?>",
				masterSolution:"<?= $_SESSION['masterSolution']?>",
			 	tags:'[' + checkedValues + ']',
			 	ownerURI:questions[counter]},
		  		function(returned_data){
					//alert(returned_data);
			  		//$("#test").html(returned_data);
			    }
		);
					
		$('.categoryChoice').attr('checked', false);

		//changes the question if it is not last
		if ( counter <= questions.length - 2 ){
			counter++;
			$('#counter').val(counter);
			$('#curQuestion').attr('src', questions[counter]);

			//enable the submit button
			$('#submit').removeAttr('disabled');
				
		}
		else{
			alert ("please Wait to find your group!");
		}
			
	}
		
</script>

<style type='text/css'>

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

</style>

<div id='tagQuestionDiv'>

	<div id='imgDiv'>
		<img id='curQuestion' src=""/>
	</div>
	
	<div id='categoryDiv'>
		Please choose the category that this question fits best <br/><br/><br/>
		<form action="">
			<input class='categoryChoice' type="checkbox" name="category[]" value="Geometry"> Geometry <br/> <br/>
			<input class='categoryChoice' type="checkbox" name="category[]" value="Trigonametry"> Trigonametry <br/> <br/>
			<input class='categoryChoice' type="checkbox" name="category[]" value="Exponential"> Exponential <br/> <br/>
			<input class='categoryChoice' type="checkbox" name="category[]" value="Algebra"> Algebra <br/> <br/>
			<input type="button" id='submit' value="Submit" onClick="nextQuestion();"/>
			<input type='hidden' id='counter' name='counter' value="0"/>
		</form>
	</div>

</div>

<?php 

require_once './footer.php';









