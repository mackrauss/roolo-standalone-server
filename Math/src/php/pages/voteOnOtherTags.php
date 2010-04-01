<?php
session_start();

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Question.php';
require_once '../dataModels/UploadedSolution.php';

$username = $_SESSION['username'];
//TODO: delete later!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$username = $_REQUEST['username'];
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
	echo "your group ($username) has not answered any questions yet!";
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
 * Find all questions answered by this group
 */
$query = "type:Question AND uri:(".$rooloClient->escapeSearchTerm(implode(' OR ', $questionUris)).")";
$questions = $rooloClient->search($query, 'metadata', 'latest');


/*
 * Find other people's tags for anwered questions
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
	
	function submitAnswer(){
		curContentDiv = $('#contentDiv'+curQuestionIdx); 
		
		// disable submit button 
		curContentDiv.find('input').attr('disabled', true);
		
		// hide the current content div
		curContentDiv.hide();

		// make sure at least one checkbox was selected
		curContentDiv.find('input[type=checkbox]:checked').size();
		
		// submit answer through ajax
//		$.ajax('/ajaxServices/');
		
		curQuestionIdx++;
		if (curQuestionIdx == numQuestions){
			$('#endOfProgram').show();
		}else{
			// show the new content div
			newContentDiv = $('#contentDiv'+curQuestionIdx);
			newContentDiv.show();
		}
		
		
	}
</script>

<?php
$i=0; 
foreach ($questions as $curQuestion) {
	$divVisibility = $i == 0 ? '' : 'display: none;'; 
?>
	<div id='contentDiv<?=$i?>' class='contentDiv' style='<?= $divVisibility?>; float: left; width: 100%;'>
		<img style='float: left;' src='<?= $curQuestion->get_path()?>' />
<?php 
	foreach ($curQuestion->get_tags() as $curTag){
?>
		<input type='checkbox' value='<?= $curTag?>' > <?= $curTag?> <br/> 
<?php	
	}
?>
		<input type='button' value='I agree with these answers' onclick='submitAnswer()' />
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