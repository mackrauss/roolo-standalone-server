<?php

session_start();

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Question.php';
require_once '../graphML/GraphML.php';
require_once '../ajaxServices/SaveUploadedSolution.php';

error_reporting(E_STRICT);

$rooloClient = new RooloClient();
$saveUploadedSolution = new  SaveUploadedSolution();
/**
 * This function is used to sort an array of objects based
 * on one of their fields. In this case by numtags.
 *
 * @param unknown_type $a
 * @param unknown_type $b
 */
function cmp($a, $b)
{
	if ($a->get_numtags() == $b->get_numtags()) {
        return 0;
    }
    return ($a->get_numtags() < $b->get_numtags()) ? -1 : 1;
}

?>
<script type="text/javascript" src="/src/js/AJAX-Upload-3.6.js"></script>
<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<?php 

if (!$_SESSION['loggedIn']) {
	header("Location:/src/php/pages/");
}
if ($_SESSION['username']!= $_REQUEST['username']){
	header("Location:/src/php/pages/");
}
$_SESSION['msg'] = "";
$greetingMsg = "Hello " . $_SESSION['username'];

$username = trim($_SESSION['username']);
$author = trim($_SESSION['username']);
$group = '';
if (strstr($author, 'algebra') > -1){
	$group = 'algebra';
}else if (strstr($author, 'graphing') > -1){
	$group = 'graphing';
}else if (strstr($author, 'functions') > -1){
	$group = 'functions';
}else if (strstr($author, 'trigonometry') > -1){
	$group = 'trigonometry';
}

// subtype of questions
$_SESSION['subject'] = "Math";

if (isset($_REQUEST['questionURIs'])){
	//convert questionURIs to array and delete the first item 
	$questionURIs = explode(',', $_REQUEST['questionURIs']); 
	$uriOfQuestion = array_shift($questionURIs);
	
	//convert questionPaths to array and delete the first item 
	$questionPaths = explode(',', $_REQUEST['questionPaths']);
	$pathOfQuestion = array_shift($questionPaths);

	//save uploaded file & create and save uploadSolution object
	$saveUploadedSolution->set_author($username);
	$saveUploadedSolution->set_questionUri($uriOfQuestion);
	//echo "uploadedfile = " .$_GET['uploadedfile'];
	$saveUploadedSolution->saveUploadedFile($_FILES['uploadedfile']);
	
}else{
	$queryUploadedSolutions = "type:UploadedSolution AND author:" . $rooloClient->escapeSearchTerm($username);
	
	$uploadedSolutions = $rooloClient->search($queryUploadedSolutions, 'metadata');
	
	$uploadedSolutionOwnerURIs = array();
	foreach($uploadedSolutions as $solution) {
		$uploadedSolutionOwnerURIs [] = '"' . $solution->get_ownerUri() . '"';
	}

	$questionWithSolURIs = implode(' OR ', $uploadedSolutionOwnerURIs);
	
	if ( sizeof($uploadedSolutionOwnerURIs) == 0 ){
		$testQuery = "type:Question AND tags:" . $rooloClient->escapeSearchTerm($group);
	}else{ 
		$testQuery = "type:Question AND tags:" . $rooloClient->escapeSearchTerm($group) . " AND -uri:(" . $questionWithSolURIs  . ")";
	}
	
	$unansweredQuestions = $rooloClient->search($testQuery, 'metadata', 'latest');
	usort(&$unansweredQuestions, "cmp");
	$questionURIs = array();
	$questionPaths = array();
	foreach ($unansweredQuestions as $curQuestion){
		$questionURIs[] = $curQuestion->get_uri();
		$questionPaths[] = $curQuestion->get_path();
	}
 			
}

?>

<script type='text/javascript'>

	var questionPaths = null;
	var qeustionURIs = null;
	var numQuestion = null;
	
    $(document).ready(function(){

		// an array that keeps all questions path
	    questionPaths = new Array('<?= implode('\', \'', $questionPaths)?>');
	    $('#questionPaths').val(questionPaths.join(','));

	    // an array that keeps all questions URI
	    questionURIs = new Array('<?= implode('\', \'', $questionURIs)?>');
	    $('#questionURIs').val(questionURIs.join(','));

    	numQuestion = questionPaths.length;

       	if (questionURIs[0] == ""){
			$('#imgDiv').html('');
			$('#imgDiv').remove();
			
			$('#uploadDiv').remove();
			$('#greetingDiv').html('<?= $greetingMsg?>');
	
			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});
			
			groupingMsg = "<h2 style='width: 100%; float: left'> There are no more questions left! </h2>";
			$('#groupingMsgDiv').html(groupingMsg);
			setTimeout("window.location.href='/src/php/pages/'", 7000);
		}else {
		   $('#signout').show();
	       $('#curQuestionImg').attr('src', questionPaths[0]);
	   	   $('#submitBtn').attr('disabled', 'disabeled');        	
	
	       $('#greetingDiv').html('<?= $greetingMsg?>');
		   $('#username').val('<?= $username ?>');
	       if (numQuestion == 1){
			   $('#curQuestionNumDiv').html('<h2> last Question </h2>');
		   }else{   
		   	   $('#curQuestionNumDiv').html('<h2>' + numQuestion + ' Questions Remaining </h2>');
		   }
	    }//end of else
    });

    
//	function submit(){
//		$('#submitBtn').attr('disabled', 'disabeled');        	
//	}

</script>


<style type='text/css'>

	#uploadSolutionDiv{
		width: 100%;
		height: 100%;
	}
	
	#greetingDiv {
		width: 100%;
		margin: 2% 0 0 4%; 
		font-size: 13px;
		//font-weight:normal;
	}
	
	#curQuestionNumDiv {
		width: 100%;
		text-align: left;
		margin-left: 3%;
	}

	#imgDiv {
		width: 40%;
		height: 150px;
		float: left;
	}
	
	#uploadDiv {
		width: 40%;
		float: left;
		margin-left: 10%;
	}
	
	.button { 
	   //outline: 0; 
	   text-decoration:none !important; 
	   position: relative; 
	   text-align: center; 
	   //zoom: 1;
	   
	    margin:5px 10px; padding:5px;  
	    font-weight:bold; font-size:1.3em;  
	    font-family:Arial, Helvetica, sans-serif;  
	    text-align:center;  
	    background:#f2f2f2;  
	    color:#3366cc;  
	    border:1px solid #ccc;  
	    width:140px;  
	    -moz-border-radius:5px; -webkit-border-radius:5px;  
	}
	
	
	#messageDiv {
		margin:5px 500px; 
		padding:5px;
		//width: 100%;
		height: 30px;
		float: left;
	}
	
	#submitBtnDiv {
		margin-left:43%;
		margin-top: 3%;
		float: left;
	
	}
</style>

<div id='uploadSolutionDiv'>

	<div id='greetingDiv'></div>
	
	<div id='groupingMsgDiv'></div>

	<div id='curQuestionNumDiv' ></div>

	<div id='imgDiv'>
		<img id='curQuestionImg' src=""/>
	</div>
	
	<div id='uploadDiv'>
		
		</br></br></br>
        <label><p>
        	Click <strong>Browse...</strong> to select your solution: <br/></p>
        </label>
		<input type='hidden' id='counter' name='counter' value="0"/>
		<form method='post' action='' enctype='multipart/form-data'>
			<input type="file" name="uploadedfile" id="uploadedfile"/>
			<div id='submitBtnDiv'>
				<input type="submit" name="submitBtn" value="Upload"  />
			</div>
			
			<input id="questionURIs" name="questionURIs" type="hidden" value=" "/>
			<input id="questionPaths" name="questionPaths" type="hidden" value=" "/>
			<input id="username" name="username" type="hidden" value=""/>
		</form> 
          	<div id='messageDiv'>
          		<label id='submitLbl'></label>
          	</div>
	</div>
</div>

<?php 

require_once './footer.php';

?>
