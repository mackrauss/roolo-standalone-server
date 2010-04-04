<?php
session_start();

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Question.php';
require_once '../dataModels/UploadedSolution.php';

$username = $_SESSION['username'];

$username = $_REQUEST['username'];
$_SESSION['username'] = $username;


if (sizeof($username) == 0){
	echo "need a username in the session or params";
	die();
}

$rooloClient = new RooloClient();

/*
 * Query for all the answers the group has given 
 */
$query = "type: UploadedSolution AND author: $username";
$answers = $rooloClient->search($query, 'metadata', 'latest');

if (sizeof($answers) == 0){
	echo "<h2>your group ($username) has not answered any questions yet!</h2>Go back and answer some questions first...";
	die();
}

/*
 * Get all questino URIs from the answers
 */
$questionUris = array();
foreach ($answers as $curAnswer){
	$questionUris[] = $curAnswer->get_ownerUri();
}

/*
 * Find all questions that have already been voted on
 */
$query = "type:VoteOnTag AND author: $username";
$existingVotes = $rooloClient->search($query, 'metadata', 'latest');

$existingVotesURIs = array();
foreach ($existingVotes as $curExistingVote){
	$existingVotesURIs[$curExistingVote->get_ownerUri()] = 1;
}
$existingVotesURIs = array_keys($existingVotesURIs);
$existingVotesURIs = implode(' OR ', $existingVotesURIs);

/*
 * Find all questions answered by this group, whose tags have not been voted on already
 */
$exclusions = mb_strlen($existingVotesURIs) == 0 ? "" : "AND -uri:(" . $rooloClient->escapeSearchTerm($existingVotesURIs) . ")";
$query = "type:Question AND uri:(".$rooloClient->escapeSearchTerm(implode(' OR ', $questionUris)).") ". $exclusions;
$questions = $rooloClient->search($query, 'metadata', 'latest');


/*
 * Find other people's tags for answered questions
 */
$questionTags = array();
foreach ($questions as $curQuestion){
	$curQuestionId = $curQuestion->get_id();
	$curQuestionUri = $curQuestion->get_uri();
	
	$query = "type:QuestionCategory AND owneruri: ".$rooloClient->escapeSearchTerm($curQuestionUri);
	$curTags = $rooloClient->search($query, 'metadata', 'latest');
	
	
	$uniqueTags = array();
	foreach ($curTags as $curTag){
		$uniqueTags[$curTag->get_title()] = 1;
	}
	
	$uniqueTags = array_keys($uniqueTags);
	sort($uniqueTags);
	$curQuestion->set_tags($uniqueTags);
}

?>

<script type='text/javascript'>
	curQuestionIdx = 0;
	numQuestions = <?= sizeof($questions)?>;
	
	$(document).ready(function (){
		$('#signout').show();

		updateQuestionNumber();
	});

	function updateQuestionNumber(){
		$('#curQuestionNum').html(curQuestionIdx+1);
	}		

	function submitAnswer(){
		curContentDiv = $('#contentDiv'+curQuestionIdx); 
		
		// disable submit button 
		curContentDiv.find('input').attr('disabled', true);
		
		// hide the current content div
		curContentDiv.hide();

		// make sure at least one checkbox was selected
		votes = curContentDiv.find('input[type=checkbox]');
		votesJson = "";
		for (i=0; i < votes.size(); i++){
			curVote = votes.get(i);
			votesJson += "'" + curVote.value + "':'" + curVote.checked +"', ";
		}

		// submit answer through ajax
		$.get("/src/php/ajaxServices/VoteOnTag.php",
		{	author:"<?= $_SESSION['username']?>",
			path:curContentDiv.find('img').attr('src'),
			ownerURI:curContentDiv.find('img').attr('uri'),
			votes:votesJson},
	  		function(returned_data){
		  		// We don't need to do anything in the call-back function
		    }
		);
		
		curQuestionIdx++;
		if (curQuestionIdx == numQuestions){
			$('#endOfProgram').show();
			setTimeout("window.location.href='/src/php/pages/'", 7000);
		}else{
			// show the new content div
			newContentDiv = $('#contentDiv'+curQuestionIdx);
			newContentDiv.show();

			updateQuestionNumber();
		}
		
		
	}
</script>

<div style='float: left; width: 100%; font-size: medium; '>
	Question <span id='curQuestionNum'></span>/<?= sizeof($questions)?>
</div>
<?php
$i=0; 
foreach ($questions as $curQuestion) {
	$divVisibility = $i == 0 ? '' : 'display: none;'; 
?>
	
	<div id='contentDiv<?=$i?>' class='contentDiv' style='<?= $divVisibility?>; float: left; width: 100%;'>
		<img style='float: left;' src='<?= $curQuestion->get_path()?>' uri='<?= $curQuestion->get_uri()?>'/>
		<div style='float: left; margin-left: 20px;'>
			<h3 style='margin-top: 40px;'>Which categories do you agree with?</h3>
<?php 
	foreach ($curQuestion->get_tags() as $curTag){
?>
			<input type='checkbox' value='<?= $curTag?>' > <?= $curTag?> <br/> 
<?php	
	}
?>
			<input type='button' value='I agree' style='margin-top: 20px;' onclick='submitAnswer()' />
		</div>
	</div>
<?php 
	$i++;
}
?>

<div id='endOfProgram' style='float: left; width: 100%; text-align: center; display: none'>
	You have reached the end of this section! <br/>
	Please wait for your teacher's instructions
</div>


















<?php 
require_once './footer.php';