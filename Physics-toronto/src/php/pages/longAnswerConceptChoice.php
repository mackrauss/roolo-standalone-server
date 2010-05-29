<?php
require_once './header.php';
error_reporting(E_STRICT | E_ALL);


// ONLY TEST CODE, REMOVE LATER
$_SESSION['loggedIn'] = true;
$_SESSION['username'] = 'group2';
// END OF TEST CODE. 

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
	$longProblemURL = "roolo://scy.collide.info/scy-collide-server/$longProblemIID.Problem";
	
	$query = "uri:".$roolo->escapeSearchTerm($longProblemURL);
	$longProblemSearchResults = $roolo->search($query, 'metadata', 'latest');
	
	$longProblem = null;
	if (count($longProblemSearchResults) != 1){
		echo "ERROR: Couldn't find the longAnswer with the given URL: ".$longProblemURL;
		die();
	}
	
	$longProblem = $longProblemSearchResults[0];
	
	/*
	 * Determine which concept Problems should be presented and get them from roolo
	 */
	$conceptProblemIIDs = Application::$longQuestionConceptQuestions[$longProblemIID];
//	print_r($conceptProblemIIDs);
	$conceptProblemUris = array(
		"roolo://scy.collide.info/scy-collide-server/".$conceptProblemIIDs[0].".Problem",
		"roolo://scy.collide.info/scy-collide-server/".$conceptProblemIIDs[1].".Problem",
		"roolo://scy.collide.info/scy-collide-server/".$conceptProblemIIDs[2].".Problem",
		"roolo://scy.collide.info/scy-collide-server/".$conceptProblemIIDs[3].".Problem"
	);
	$conceptProblemUrisStr = "(" . implode(" OR ", $roolo->escapeUri($conceptProblemUris)) . ")";
	$query = "uri:".$conceptProblemUrisStr;
	$conceptProblems = $roolo->search($query, 'metadata', 'latest');
	
	/*
	 * render view
	 */
?>

<div id="container2">
<div id="middle-top">
	<div id="questionHeading">
	  <p><b>Last Question</b></p>
    </div>
</div>
<div id="middle-center">

    <div id="questionSection">
    <p>Below is a physics problem. Select the concept question on the right that most closely resembles the provided problem.</p>
    	<img src="<?= $longProblem->path?>" width="454" height="320" class="problem" />
  	</div>
    <div id="conceptQuestion">
    <form id="step3" action='/src/php/pages/longAnswer.php' method='GET'>
<?php 
	foreach ($conceptProblems as $curConceptProblem){
?>
    <label class="radioButton">
    	<input type="radio" name="selectedConceptProblem" value="<?= $curConceptProblem->uri?>"/>
    	<img src="<?= $curConceptProblem->path ?>" width="250" height="190" />
    </label>
    <br/>
<?php 
	}
?>
    <input name="submit" type="submit" value="SUBMIT" class="btn" />
    </form>
    </div>
</div>
<div id="middle-bottom"></div>


<?php
} 
/*
 * If user has already answered  a LongAnswer,
 */
else {
?>
You've already answered your last question. If you believe this is an error, please let your teacher know! Otherwise, please log out. 
<?php
}
?>




<?php 
require_once './footer.php';
?>