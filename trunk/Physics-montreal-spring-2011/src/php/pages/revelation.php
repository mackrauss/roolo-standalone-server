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

$rooloClient = new RooloClient();

// Get the problem
$query = "uri:".$rooloClient->escapeUri($problemUri);
$results = $rooloClient->search($query, 'metadata', 'latest');
$mainProblem = $results[0];
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
		height: 500px;
	}
	
	#correctAnswer {
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
	
	#nextQuestionSection {
		width: 90%;
		text-align: right;
		margin-top: 20px;
	}
</style>

<div id="container2">
	<div id="middle-top">
		<div id="questionHeading">
			And the correct answer is...
		</div>
	</div>
	<div id="middle-center">
		<div id="correctAnswer">
			<?= $mainProblem->mcmastersolution ?>
		</div>
	    <div id="questionSection">
	    	<img id='curQuestion' src="<?= $mainProblem->path ?>" width="454" height="320" class="problem"/>
	  	</div>
	  	
	  	<div id='nextQuestionSection'>
	  		<input type='button' id='nextProblemBtn' class='btn' value='NEXT' onclick="window.location.href = '/src/php/pages/tar.php'" />
	  	</div>
  	</div>
	<div id="middle-bottom">
	
	</div>
</div>

<?php 
require_once './footer.php';
?>