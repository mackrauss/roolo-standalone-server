<?php
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';
require_once '../dataModels/RunConfig.php';
require_once '../Application.php';

if (!$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
}

$_SESSION['msg'] = "";
$username = $_SESSION['username'];
$runId = $_SESSION['runId'];
$greetingMsg = "Signed in as <b> " . $username . "</b>";
$noMoreProblemMsg1 = 'Great job completing this step!';
$noMoreProblemMsg2 = 'You will be logged out in 10 seconds.';


$rooloClient = new RooloClient();

//Retrive related RunConfig elo for runchoicelimit element
$runConfigObj = new RunConfig();
$runConfigQuery = "type:RunConfig AND runid:" . $runId;
$runConfigArray = $rooloClient->search($runConfigQuery, 'metadata');
$runConfigObj = $runConfigArray[0];
$runChoiceLimit = $runConfigObj->runchoicelimit;
$choicesArray = explode( ","  , $runChoiceLimit );

$query ='';
$results = array();

//Retrieve all problems object for specific runID
$query = "type:Problem AND runid:" . $runId ;
$allProblems = $rooloClient->search($query, 'metadata', 'latest');
$numProblems = sizeof($allProblems);

//Determine all problems object without solutions for the specific user
$query = "type:Solution AND author:" . $username . " AND runid:" . $runId;
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
$numUnansweredProblems = sizeof($results);
$numAnsweredProblems = $numProblems - $numUnansweredProblems;

// get the latest solution, and check if it has an AdvisedSolution or not 
// if not, forward them to the reflection page
if (sizeof($authorSolutions) > 0){
	$latestSolution = $authorSolutions[sizeof($authorSolutions)-1];
	$latestSolutionProblemUri = $latestSolution->owneruri;
	$latestSolutionSelectedChoice = $latestSolution->selectedchoice;
	
	$query = "type:AdvisedSolution AND owneruri:".$rooloClient->escapeUri($latestSolutionProblemUri)." AND author:$username AND runid:$runId";
	$advisedSolutions = $rooloClient->search($query, 'metadata', 'latest');
	if (sizeof($advisedSolutions) == 0){
		echo "<script type='text/javascript'>window.location.href = '/src/php/pages/reflection.php?problemUri=$latestSolutionProblemUri&selectedChoice=$latestSolutionSelectedChoice';</script>";
		die();
	}
}


if ($numUnansweredProblems == 0){
?>
	<script type='text/javascript'>
		$(document).ready(function(){
			$('#middle-top').remove();
			$('#middle-center').remove();
			$('#middle-bottom').remove();

			// show end of activity message
			$('#endOfActivityMessage').show();
			delayAndLogout();
		});
	</script>
<?php 
}else{
	$mainProblem = $results[0];
?>
	<script type='text/javascript'>
		function saveQuestion(){
			//select the selected item in dropdowns
			var options = $("#selectPart").find("input[type:'radio']:checked").map(function(){
				   return $(this).val();
			}).get().join(", ");
	
			var selectedChoice = $("input[name='choice']:checked").val();
			if (selectedChoice == null || selectedChoice.length == 0){
				alert("Please choose an answer");
				return;
			}
			var selectedCategory = new Array();
			$("input[name='categoryArray[]']:checked").each(function() {selectedCategory.push($(this).val());});
			var reason = $('#rationale').val();
			if (reason == ''){
				alert("Please provide a reasoning for your answer");
				return;
			}
			var category = selectedCategory.join(', ');
	
			var categoryAndOptions;
			if (category != '' && options != '' )
				categoryAndOptions = category + ", " + options;
			if (category != '' && options == '' )
				categoryAndOptions = category;
			if (category == '' && options != '' )
				categoryAndOptions = options;

			if (categoryAndOptions == undefined)
				categoryAndOptions = '';
			
			// OK WE ARE NOW CLEAR TO SUBMIT THE ANSWER AND REDIRECT TO REFLECTION PAGE
			//disable the submit button
			$('#submitBtn').attr('disabled', 'disabled');
								
			//Ajax call to send group, selectedChoice, Owneruri, rationale
			$.get("/src/php/ajaxServices/saveMultiplechoice.php",
				{username:"<?= $username?>",
				 runId:"<?= $runId ?>",
				 choice: selectedChoice,
				 ownerURI:'<?= $mainProblem->uri ?>',
				 reason: reason,				 
				 category: category,
				 options: options,
				 categoryAndOptions: categoryAndOptions	
				},
		  		function(returned_data){
			  		// We don't need to do anything in the call-back function
			    }
			);
	
			// now we can redirect to the reflection page
			setTimeout("window.location.href = '/src/php/pages/reflection.php?problemUri=<?= $mainProblem->uri ?>&selectedChoice=' + selectedChoice", 2000);
		}
	</script>
<?php 	
}
?>

<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<script type='text/javascript'>
	function delayAndLogout(){
		setTimeout ("logout()", 5000);
	}
	
	function logout(){
		window.location = "/src/php/ajaxServices/logout.php";
	}

	function showMcPart(){
		flag = "true";
		$('#elements').hide('slow');
		$('#mc').show('slow');
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
	
	#endOfActivityMessage {
		font-size: 16px;
		height: 50px;
		margin: 20px 0;
		width: 100%;
		float: left;
		text-align: center;
		display: none; 
	}
</style>

<div id="container2">

<div id="middle-top">
	<div id="questionHeading">
		<p><b>Question <?= ($numAnsweredProblems + 1) ?> of <?= $numProblems ?></b></p>
	</div>
</div>

<div id="middle-center">
    <div id="questionSection">
    	<img id='curQuestion' src="<?= $mainProblem->path ?>" width="454" height="320" class="problem"/>
  	</div>
  	
	<div id="answerSection" >
		<form id="round1" name="form1" method="post" action="feedback.php">
			<dl id='elements' >
				<dt>1. Check the corresponding elements that you thought are important in answering the question:</dt>

			  	<dd>
			  		<?php 
						foreach (Application::$problemCategories as $curProblemCategory) {
					?>
						  		<label><input class="box" type="checkbox" name="categoryArray[]" value="<?= $curProblemCategory?>" /><?= $curProblemCategory?></label><br/>
					<?php 
						}
					?>
			  	</dd>
			  	<dd id="selectPart">
			  		<?php 
			  			$i = 0;
						foreach (Application::$dropdownsItems as $dropdownItemsKey => $dropdownItems) {
								$i++;
								echo "<b>" . $dropdownItemsKey. "</b> <br/>";
					?>
								<?php 
									for ($j=1; $j < sizeof($dropdownItems); $j++){
								?>
										<input type="radio" name="<?= $dropdownItemsKey?>" value="<?= $dropdownItems[$j]?>" /> <?= $dropdownItems[$j] ?> <br/>
								<?php 
									}
								?>
								<br/>
					<?php 
						}
					?>
			 	</dd>
			 	<input name="submit" type="button" value="NEXT PAGE" class="btn" onClick="showMcPart(); scroll(0,0);" />
			</dl>
			<dl id='mc' style='display: none'>
				<dt>2.Select the correct answer:</dt>
				<dd>
					<?php 
						foreach ($choicesArray as $choice) {
					?>
							<label class="radioButton"><input type="radio" name="choice" value="<?= trim($choice) ?>" /><?= trim($choice) ?></label>
					<?php		
						}
					?>
				</dd>
				<dt> 3. Provide a reasoning for the answer that you chose above: </dt>			  	
				<textarea id="rationale" rows="20" cols="40"></textarea>
				
				<input name="submit2" type="button" value="SUBMIT" class="btn" onClick="saveQuestion(); scroll(0,0);" />		  
			  	
			</dl>
		</form>
	</div>
</div>	
<div id="middle-bottom">

</div>



<div id='endOfActivityMessage'>
	You have already completed this activity! You will be logged out momentarily...
</div>
 
 

<?php
require_once './footer.php';
?>