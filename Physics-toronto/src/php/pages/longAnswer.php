<?php
require_once './header.php';
error_reporting(E_STRICT | E_ALL);


if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
}


require_once '../RooloClient.php';
require_once '../Application.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';


$username = $_SESSION['username'];
$roolo = new RooloClient();

/*
 * check if this user has done a LongAnswer already
 */
$query = "type:LongAnswer AND author:".$username;
$existingLongAnswers = $roolo->search($query, 'metadata', 'latest');

/*
 * If user has not answered a LongAnswer before, 
 */
if (count($existingLongAnswers) == 0){
	/*
	 * Determine which long Problem they should answer and get it from roolo
	 */
	$longProblemIID = Application::$groupLongQuestions[$username];
//	echo $longProblemIID;
//	$longProblemURL = "roolo://scy.collide.info/scy-collide-server/$longProblemIID.Problem";
	
	$query = "type:Problem AND uniquequestionid:".$longProblemIID;
	$longProblemSearchResults = $roolo->search($query, 'metadata', 'latest');
	
	$longProblem = null;
	if (count($longProblemSearchResults) != 1){
		echo "ERROR: Couldn't find the longAnswer with the given URL: ".$longProblemURL;
		die();
	}
	
	$longProblem = $longProblemSearchResults[0];
	
	/*
	 * Get the selected concept problem from the request parameters
	 */
	$conceptProblemUri = $_REQUEST['selectedConceptProblem'];
	$query = "uri:".$roolo->escapeUri($conceptProblemUri);
	$results = $roolo->search($query, 'metadata', 'latest');
	$selectedConceptProblem = $results[0];
	
	
	/*
	 * Get all formulas, their IDs, names and pics
	 */
	$formulas = Application::$forumulas;
	
	/*
	 * Get all Problem Categories 
	 */
	$problemCategories = Application::$problemCategories;

	/*
	 * render view
	 */
?>
<script type="text/javascript">
conceptProblemUri = "<?= $conceptProblemUri ?>";
longProblemUri = "<?= $longProblem->uri?>";
longProblemPath = "<?= $longProblem->path?>";

function submitLongAnswer(){
	// get the multiple choice answer
	var selectedMultipleChoice = $('[name=multipleChoice]:checked');
	if (selectedMultipleChoice.length == 0){
		alert('You must choose an answer for question 1');
		return;
	}
	var multipleChoice = selectedMultipleChoice.val();

	// get the categories chosen
	var selectedCategories = $('[name=problemCategory]:checked');
	if (selectedCategories.length == 0){
		alert('You must choose at least one principle in question 2');
		return;
	}
	
	var categories = new Array();
	selectedCategories.each(function(i,selected){
		categories[i] = $(selected).val();
	});
	categories = categories.join(', ');

	// get the formulas chosen
	var selectedFormulas = $('[name=formula]:checked');
	if (selectedFormulas.length == 0){
		alert('You must choose at least one formula in question 3');
		return;
	}

	var formulas = new Array();
	selectedFormulas.each(function(i,selected){
		formulas[i] = $(selected).val();
	});
	formulas = formulas.join(', ');

	// get the rationale
	var rationale = $('[name=rationale]').val().trim();
	if (rationale.length == 0){
		alert('Please write a rationle in question 4');
		return;
	}else if (rationale.length > 300){
		alert('Please make sure your rationale is not longer than 300 characters');
		return;
	}

	// disable submit button
	$('#submitBtn').attr("disabled", true);
	
	// call the Ajax service to record the LongAnswer submitted
	$.post('/src/php/ajaxServices/saveLongAnswer.php', 
		{
			'longProblemUri': longProblemUri,
			'longProblemPath': longProblemPath,
			'conceptProblemUri': conceptProblemUri,
			'multipleChoice': multipleChoice, 
			'categories': categories,
			'formulas': formulas,
			'rationale': rationale
		}, function(response){
			console.log(response);
		}
	);
	
}

function rationaleChanged(){
	$('#numRationaleCharsLeft').html(300 - $('[name=rationale]').val().length);
}
</script>

<style type="text/css">
	.formulaLabel {
		color:#1E7EC8;
		font-size:small;
		font-weight:bold;
		line-height:24px;
	}
</style>
<div id="container2">
<div id="middle-top">
	<div id="questionHeading">
	  <p><b>Last Question</b></p>
    </div>
</div>
<div id="middle-center2">
    <div id="questionSection">
    	<img src="<?= $longProblem->path ?>" width="454" height="320" class="problem" />
  	</div>
	  	
  	<div id="conceptQuestion2">
   		<p>Selected Concept Problem</p>
	    <img src="<?= $selectedConceptProblem->path ?>" width="250" height="190"/><br/>
    </div>
	    
    <div id="answerSection4">
	  	<form id="round1" name="form1" method="post" action="feedback.php">
	  	<dt>1. Select the correct answer to the LONG problem:</dt>
	    <dd>
	    	<label class="radioButton"><input type="radio" name="multipleChoice" value="A"/>A</label>
	 		<label class="radioButton"><input type="radio" name="multipleChoice" value="B"/>B</label>
	  		<label class="radioButton"><input type="radio" name="multipleChoice" value="C"/>C</label>
	  		<label class="radioButton"><input type="radio" name="multipleChoice" value="D"/>D</label>
	  	</dd>
	 	<dt>2. Check the corresponding elements that are shown in the LONG problem:</dt>
	  	<dd>
<?php 
	foreach ($problemCategories as $curProblemCategory) {
?>
	  		<label><input type="checkbox" name="problemCategory" value="<?= $curProblemCategory?>" /><?= $curProblemCategory?></label><br/>
<?php 
	}
?>
	  	</dd>
	    <dt>3. Check off the corresponding formulas that you would use to solve the LONG problem.</dt>
	    <dd>
<?php 
	foreach ($formulas as $curFormulaName => $curFormula){
?>
	    	<label>
	    		<table>
	    			<tr>
	    				<td> <input type="checkbox" name="formula" value="<?= $curFormula['id']?>" /> </td>
	    				<td class='formulaLabel'> <?= $curFormulaName?> </td> 
	    				<td> <img src="<?= $curFormula['path'] ?>" /> </td>
	    			</tr>
	    		</table> 
	    	</label>
	    	<br/>
<?php 
	}
?>
	    </dd>
	    <dt>4. Tell us why you chose the formulas you chose and how you would use those formulas to solve the problem.</dt>
	  	<dd><textarea name="rationale" cols="60" rows="12" onKeyUp='rationaleChanged();'></textarea></dd>
	
	  	<p><span id='numRationaleCharsLeft'>300</span> characters left</p>
	  	<input id='submitBtn' name="submit" type="button" value="SUBMIT" class="btn" onclick="submitLongAnswer();"/>
	  	</form>
	<!-- end #answerSection -->
	</div>
</div>
<div id="middle-bottom">
</div>

<?php
} 
/*
 * If user has already answered  a LongAnswer,
 */
else {
?>
You"ve already answered your last question. If you believe this is an error, please let your teacher know! Otherwise, please log out. 
<?php
}
?>




<?php 
require_once './footer.php';
?>