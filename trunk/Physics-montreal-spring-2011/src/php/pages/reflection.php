<?php
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';
require_once '../dataModels/AdvisedSolution.php';
require_once '../dataModels/RunConfig.php';
require_once '../Application.php';

if (!$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
}

$username = $_SESSION['username'];
$runId = $_SESSION['runId'];

$problemUri = $_REQUEST['problemUri'];
$selectedChoice = $_REQUEST['selectedChoice'];

$rooloClient = new RooloClient();

//Retrive related RunConfig elo for runchoicelimit element
$runConfigObj = new RunConfig();
$runConfigQuery = "type:RunConfig AND runid:" . $runId;
$runConfigArray = $rooloClient->search($runConfigQuery, 'metadata');
$runConfigObj = $runConfigArray[0];
$runChoiceLimit = $runConfigObj->runchoicelimit;
$choicesArray = explode( ","  , $runChoiceLimit );

// Get the problem
$query = "uri:".$rooloClient->escapeUri($problemUri);
$results = $rooloClient->search($query, 'metadata', 'latest');
$mainProblem = $results[0];
$mainProblemMasterSolution = $mainProblem->mcmastersolution;
$mainProblemBestWrongSolution = $mainProblem->bestwrongsolution;

// Choose which two solutions to display
$choice1 = $mainProblemMasterSolution;
$choice2 = null;
if ($mainProblemMasterSolution == $selectedChoice){				// RIGHT ANSWER
	$choice2 = $mainProblemBestWrongSolution;
}else{															// WRONG ANSWER
	$choice2 = $selectedChoice;
}

// Get all solutions given for each choice
$choice1Solutions = getSolutionRationales($choice1, $runId, $username, $problemUri, $rooloClient);
$choice2Solutions = getSolutionRationales($choice2, $runId, $username, $problemUri, $rooloClient);

// randomize the rationales
shuffle(&$choice1Solutions);
shuffle(&$choice2Solutions);

// pick the top 10 rationales
$choice1Solutions = array_slice($choice1Solutions, 0, 10);
$choice1Solutions = array_slice($choice1Solutions, 0, 10);

function getSolutionRationales ($selectedChoice, $runId, $username, $problemUri, $rooloClient){
	$query = "type:Solution AND owneruri:".$rooloClient->escapeUri($problemUri)." AND runid:$runId AND selectedchoice:$selectedChoice";
	$solutions = $rooloClient->search($query, 'metadata', 'latest');
	
	// filter out the current user's own rationale if any
	$filteredSolutions = array();
	foreach ($solutions as $curSolution){
		$solutionAuthor = $curSolution->author;
		if ($solutionAuthor != $username){
			$filteredSolutions[] = $curSolution;
		}
	}

	return $filteredSolutions;
}

?>


<style type='text/css'>
	div#questionHeading {
		height: 50px;
		width: 90%;
		margin:0;
		padding-top:30px;
		padding-left:40px;
		font-family:Arial, Helvetica, sans-serif;
		font-size:x-large;
		color:#1E7EC8;
	}
	
	div#questionSection {
		width: 90%;
		text-align: center;
		float: none;
	}
	
	div#middle-center {
		height: 2000px;
	}
	
	div#answerSection {
		top: 0;
	}
	
	#sampleSolutions {
		float: left;
		width: 100%;
		padding: 50px 30px 0px 30px;
		margin-bottom: 60px;
	}
	
	#sampleSolutionLeft {
		float: left; 
		width: 45%;
	}
	
	#sampleSolutionRight {
		float: left;
		width: 45%;
		margin-left: 20px;
	}
	
	.sampleSolutionChoice {
		border: 5px solid black;
		font-size: 40px;
		color: black;
		width: 60px;
		height: 60px;
		padding: 10px 0 5px 25px;
		margin-left: auto;
		margin-right: auto;
		margin-bottom: 40px;
	}
	
	.rationale {
		width: 100%;
		float: left;
		margin-bottom: 20px;
		border-bottom: 1px solid #82B339;
		padding-bottom: 5px;
	}
	
	.rationaleVote {
		width: 100%;
		float: left;
		margin: 5px 0px;
	}
	
	.rationaleVote .up {
		background-image: url("/resources/images/up.png");
		margin-left: 5px;

		float: right;		
		width: 20px;
		height: 20px;
	}
	
	.rationaleVote .down {
		background-image: url("/resources/images/down.png");
		margin-left: 5px;
		
		float: right;		
		width: 20px;
		height: 20px;
	}
	
	.rationaleVote .upSelected {
		background-image: url("/resources/images/up_selected.png");
		margin-left: 5px;
		
		float: right;		
		width: 20px;
		height: 20px;
	}
	
	.rationaleVote .downSelected {
		background-image: url("/resources/images/down_selected.png");
		margin-left: 5px;
		
		float: right;		
		width: 20px;
		height: 20px;
	}
	
	.sampleSolutionHeader {
		color:#1E7EC8;
		font-family:Arial,Helvetica,sans-serif;
		font-size:large;
		width: 100%;
		float: left;
		margin-bottom: 15px;
	}
</style>

<script type='text/javascript'>
	votes = {};
	
	$(document).ready(function(){
		$('.rationaleVote .up').click(function(){
			// select UP
			$(this).attr('class', 'upSelected');

			// de-select DOWN
			$(this).parent().find('.downSelected').attr('class', 'down');

			var solutionUri = $(this).parent().attr('solutionUri');
			// adjust vote count
			votes[solutionUri] = 1;
		});

		$('.rationaleVote .down').click(function(){
			// select DOWN
			$(this).attr('class', 'downSelected');

			// de-select UP
			$(this).parent().find('.upSelected').attr('class', 'up');

			var solutionUri = $(this).parent().attr('solutionUri');
			// adjust vote count
			votes[solutionUri] = -1;
		});
	});
	
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

		var rationaleIsUnchanged = $('#rationaleIsUnchanged').is(':checked');
		var reason = null;
		if (rationaleIsUnchanged){
			reason = "UNCHANGED";
		}else{
			reason = $('#rationale').val();
			if (reason == ''){
				alert("Please provide a reasoning for your answer");
				return;
			}
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
			 categoryAndOptions: categoryAndOptions, 
			 solutionType: 'AdvisedSolution'
			},
	  		function(returned_data){
		  		// We don't need to do anything in the call-back function
		    }
		);

		//Ajax call to save the votes
		var votesJson = JSON.stringify(votes);

		$.get("/src/php/ajaxServices/saveSolutionVotes.php",
				{
					votes: votesJson,
					username:"<?= $username?>",
					runId:"<?= $runId ?>"
				}, 
				function(data){
					// We don't need to do anything in the call-back function
				}	
		);

		// now we can redirect to the revelation page
//		window.location.href = '/src/php/pages/revelation.php?problemUri=<?= $mainProblem->uri ?>';
	}
</script>

<div id="container2">
	<div id="middle-top">
		<div id="questionHeading">
			Now let's reflect on how others answered the same question...
		</div>
	</div>
	<div id="middle-center">
	    <div id="questionSection">
	    	<img id='curQuestion' src="<?= $mainProblem->path ?>" width="454" height="320" class="problem"/>
	  	</div>
	  	
	  	
	  	
	  	
	  	
	  	
	  	
	  	
	  	<div id='sampleSolutions'>
	  		<div id='sampleSolutionLeft'>
	  			<div class='sampleSolutionChoice'>
	  				<?= $choice1 ?>
	  			</div>
	  			<div class='sampleSolutionHeader'>
	  				Why others chose <?= $choice1 ?>: 
	  			</div>
<?php 
	foreach ($choice1Solutions as $curSolution){
?>
		<div class='rationale'>
			<?= $curSolution->rationale ?>
			<div class='rationaleVote' solutionUri='<?= $curSolution->uri?>' >
				<div class='down'></div>
				<div class='up'></div>
			</div>
		</div>
<?php 
	}
?>
	  			
	  		</div>
	  		
	  		<div id='sampleSolutionRight'>
	  			<div class='sampleSolutionChoice'>
	  				<?= $choice2 ?>
	  			</div>
	  			<div class='sampleSolutionHeader'>
	  				Why others chose <?= $choice2 ?>: 
	  			</div>
	  			
<?php 
	foreach ($choice2Solutions as $curSolution){
?>
		<div class='rationale'>
			<?= $curSolution->rationale ?>
			<div class='rationaleVote' solutionUri='<?= $curSolution->uri?>' >
				<div class='down'></div>
				<div class='up'></div>
			</div>
		</div>
<?php 
	}
?>

	  		</div>
	  	</div>
	  	
	  	
	  	
	  	
	  	
	  	
	  	
	  	
	  	<div id="questionHeading">
	  		Use your new insights to answer the question again:
	  	</div>
	  	<div id="answerSection" >
			<form id="round1" name="form1" method="post" action="feedback.php">
				<dl>
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
					<input type='checkbox' name='rationaleIsUnchanged' id='rationaleIsUnchanged' onclick="$(this).is(':checked') && $('#rationale').slideUp('slow') || $('#rationale').slideDown('slow');">
						Same as before
					</input>		  	
					<textarea id="rationale" rows="20" cols="40"></textarea>
					
					<input name="submitBtn" id="submitBtn" type="button" value="SUBMIT" class="btn" onClick="saveQuestion()" />		  
				</dl>
			</form>
		</div>
	</div>
	<div id="middle-bottom">
	
	</div>
</div>

<?php 
require_once './footer.php';
?>