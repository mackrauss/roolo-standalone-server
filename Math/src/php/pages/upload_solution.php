<?php
require_once './header.php';
?>
<script type="text/javascript" src="/src/js/AJAX-Upload-3.6.js"></script>

<?php 
require_once '../RooloClient.php';
require_once '../dataModels/Question.php';
require_once '../dataModels/UploadedSolution.php';

error_reporting(E_STRICT);


// check username variable has been sent
if(isset($_GET['username'])){
	$_SESSION['username'] = $_GET['username'];
	$greetingMsg = "Hello " . $_SESSION['username'];
}else {
	$_SESSION['username'] = '';
	$greetingMsg = 'username has not been set !!!';
}
// check this has been taged by teacher or student
//if(isset($_GET['masterSolution'])){
//	$_SESSION['masterSolution'] = $_GET['masterSolution'];
//}else { // zero means false and one means true
//	$_SESSION['masterSolution'] = 0;
//}

// check this category
if(isset($_GET['category'])){
	$_SESSION['category'] = $_GET['category'];
}else { 
	$_SESSION['category'] = 'geometry';
}

// subtype of questions
$_SESSION['subject'] = "Math";


// retrieve questions from repository
$rooloClient = new RooloClient();
$query = 'type:Question';

//$allQuestions = $rooloClient->search($query, 'metadata', 'latest');
$allQuestions = $rooloClient->search($query, 'metadata', 'latest');
//echo "size of questions =" .sizeof($allQuestions);
/*
 TODO
*/ 



$query = "type:UploadedSolution AND author:" . $_SESSION['username'];

//$answeredQuestions = $rooloClient->search($query, 'metadata', 'latest');
$uploadedSolutions = $rooloClient->search($query, 'metadata');
//echo "</br>size of uploadedSolutions =" .sizeof($uploadedSolutions);
$solutionObject = new UploadedSolution();
$questionObject = new Question();
$results= array();
if (sizeof($uploadedSolutions) == 0){
	$results = $allQuestions;
}else{
	for($i=0; $i<sizeof($allQuestions); $i++){
		$questionObject = $allQuestions[$i];
		$uri = $questionObject->get_uri();
		//echo "</br></br>uri[".$i."] = ".$uri;
		$found = FALSE;
		for($j=0; $j<sizeof($uploadedSolutions); $j++){
			$solutionObject = $uploadedSolutions[$j];
			$ownerURI = $solutionObject->get_ownerUri();
			//echo "</br>ownerUri[".$j."] = ".$ownerURI;
			if ($ownerURI == $uri){
				$found = TRUE;
				//unset($allQuestions[$j]);
			}
		}
		//echo "</br>found = ".$found;
		if (!$found){
			//echo "</br>shoud be in result = ".$questionObject->get_uri();
			array_push($results, $questionObject);
		}
	}
}
//echo "size of result = ". sizeof($results);
//////////////////////////////////////	
//$rooloClient = new RooloClient();
//$query = "type:Question AND subtype:". $_SESSION['subject'];
//$results = $rooloClient->search($query, 'metadata', 'latest');
	if (sizeof($results) != 0){
		for ($i=0; $i< sizeof($results); $i++){
			$questionObject = new Question();
			$questionObject = $results[$i];
			$questions[$i] = $questionObject->get_path();
			$questionsURI[$i] = $questionObject->get_uri();
			
		}
	}else{
		$message =  'There are no more questions to solve!';
	}

?>

<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<script type='text/javascript'>

	// an array that keeps all questions path
	var questions = new Array('<?= implode('\', \'', $questions)?>');

	// an array that keeps all questions URI
	var questionsURI = new Array('<?= implode('\', \'', $questionsURI)?>');

	var numQuestion = questions.length;
	var curQuestionNum = 1;
	
	
    $(document).ready(function(){
		if ('<?= sizeof($results) ?>' == 0){
			$('#imgDiv').html('');
			$('#imgDiv').remove();
			
			$('#uploadDiv').remove();
			$('#greetingDiv').html('<?= $greetingMsg?>');

			$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});
			
			groupingMsg = "<h2 style='width: 100%; float: left'> Please wait for the system to send you to a group</h2>";
			groupingMsg += "<input id='getGroupButton' type='button' value='What is my group' onClick='checkGroup()'/>";
			groupingMsg += "<h4 style='width: 100%; float: left'>" + "<?= $message ?>" + "</h4>"; 
			$('#groupingMsgDiv').html(groupingMsg);
		}else {
	    	ajaxUpload = new AjaxUpload(
	    	    'uploadBtn',
	    	    {  
		        	action: '/src/php/ajaxServices/saveSolution.php',  
		            //Name of the file input box  
		            name: 'uploadedfile', 
		            data: { },
		            autoSubmit: false,
		                         
		            onSubmit: function(file, ext){
		            	  
						$('#messageDiv').empty();
			            this.setData ( {'author'   : '<?= $_SESSION['username']?>',
			               	            'ownerURI' : $('#curQuestionId').val()});  
			            this.disable();
					},
	
					onChange: function(file,ext){
		            	$('#messageDiv').html('<label>Click <strong>Submit...</strong> to upload your solution: <label/>' + file);
		           	    $('#submitBtn').removeAttr('disabled');
		           	    $('#submitBtn').css({'color' : '#3366CC'});
		            },
		              
		            onComplete: function(file, response){
						//alert ("response is = '" + response + "'");
						response = response.substr(0,7);
			            if(response === "success"){  
			            	var counter = $('#counter').val();
								
			   	    		//changes the question if it is not last
			   	    		if ( counter <= questions.length - 2 ){
			   	    			counter++;
			   	    			$('#counter').val(counter);
			   	    			$('#curQuestionImg').attr('src', questions[counter]);
			   	
			   	    			$('#curQuestionId').val(questionsURI[counter]);
			   	            	this.enable();
		
			   	            	// increment the current number of the question
			   	     			curQuestionNum++;
			   	     			$('#curQuestionNumDiv').html('<h2> Question ' + curQuestionNum + '/' + numQuestion + '</h2>');
				           	    $('#submitBtn').css({'color' : '#cccccc'});
			   	     			
			   	    		}
			   	    		else{
				   	 			$('#imgDiv').html('');
				   				$('#imgDiv').remove();
			
				   				$('#uploadDiv').remove();
			
				   				$('#groupingMsgDiv').css({'width' : '100%', 'height' : '18%'});
		
				   				groupingMsg = "<h2 style='width: 100%; float: left'> Please wait for the system to send you to a group</h2>";
				   				groupingMsg += "<input id='getGroupButton' type='button' value='What is my group' onClick='checkGroup()'/>";
				   				$('#groupingMsgDiv').html(groupingMsg);
				   				$('#curQuestionNumDiv').html('');
			   	    		}
						} else{
							var par = $('<p>');
							var str = '<B>' + file + '</B>' + '   has not saved!'; 
							par.html(str);
		    	            par.appendTo('#messageDiv');
		    	           	this.enable();
			            }
	 	              
	           		}  
	       		});// end of define ajaxUpload
	       $('#curQuestionImg').attr('src', questions[0]);
	       $('#curQuestionId').val(questionsURI[0]);
	   	   $('#submitBtn').attr('disabled', 'disabeled');        	
	
	       $('#greetingDiv').html('<?= $greetingMsg?>');
		   $('#curQuestionNumDiv').html('<h2> Question ' + curQuestionNum + '/' + numQuestion + '</h2>');
			
			$('Button').hover(
				function(){ 
					$(this).addClass("hover"); 
				},
				function(){ 
					$(this).removeClass("hover"); 
				}
			)
	    }//end of else
    });

    
	function submit(){
		$('#submitBtn').removeClass("hover"); 
		$('#submitBtn').attr('disabled', 'disabeled');        	
		ajaxUpload.submit();	
	}

</script>
<script type='text/javascript'>
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
	
//	#uploadButtonDiv {  
//        margin:5px 70px;
//        padding:5px;  
//        font-weight:bold; font-size:1.3em;  
//        font-family:Arial, Helvetica, sans-serif;  
//        text-align:center;  
//        background:#f2f2f2;  
//        color:#3366cc;  
//        border:1px solid #ccc;  
//        width:150px;  
//        cursor:pointer !important;  
//        -moz-border-radius:5px; -webkit-border-radius:5px;  
//    }  
//	
//	/* 
//	We can't use ":hover" preudo-class because we have
//	invisible file input above, so we have to simulate
//	hover effect with javascript. 
//	 */
//	#uploadButtonDiv.hover {
//		background: url(button.png) 0 56px;
//		color: #95A226;
//		cursor:pointer !important;  
//	}
//	#submitBtnDiv {
//        margin:5px 70px; padding:5px;  
//        font-weight:bold; font-size:1.3em;  
//        font-family:Arial, Helvetica, sans-serif;  
//        text-align:center;  
//        background:#f2f2f2;  
//        color:#3366cc;  
//        border:1px solid #ccc;  
//        width:150px;  
//        cursor:pointer !important;  
//        -moz-border-radius:5px; -webkit-border-radius:5px;  
// 	}
//	#submitBtnDiv.hover {
//		background: url(button.png) 0 56px;
//		color: #95A226;
//		cursor:pointer !important;  
//	}
	
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
	
	.hover {
			//background: url(button.png) 0 56px;
			background:#ffffff;
			//color: #95A226;
			cursor:pointer !important;  
	}
	
	#messageDiv {
		margin;5px 500px; 
		padding:5px;
		//width: 100%;
		height: 30px;
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
            Click <strong>Browse...</strong> to select your solution: <br/></p></label>
            
			<input type="hidden" id="curQuestionId" name="curQuestionId" value="" />
			<input type='hidden' id='counter' name='counter' value="0"/>

 			<div id='buttonsDiv'>
  				<input type="button" id='uploadBtn' value="Browse" class="button" />
				<input type ="button" id='submitBtn' class="button" value="Submit" style="color:#cccccc" onClick='submit()'/>
           	</div>
          	<div id='messageDiv'>
          		<label id='submitLbl'></label>
          	</div>
	</div>
</div>

<?php 

require_once './footer.php';

?>
