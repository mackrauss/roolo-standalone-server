<?php
require_once './header.php';
?>
<script type="text/javascript" src="/src/js/AJAX-Upload-3.6.js"></script>

<?php 
require_once '../RooloClient.php';

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
$query = "type:Question AND subtype:". $_SESSION['subject'];
$results = $rooloClient->search($query, 'metadata', 'latest');
	if (sizeof($results) != 0){
		for ($i=0; $i< sizeof($results); $i++){
			$questionObject = new Question();
			$questionObject = $results[$i];
			$questions[$i] = $questionObject->get_path();
			$questionsURI[$i] = $questionObject->get_uri();
			
		}
	}else{
		echo 'There is no question in repository!';
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

    	new AjaxUpload('uploadButtonDiv', {  
            action: '/src/php/ajaxServices/saveSolution.php',  
            //Name of the file input box  
            name: 'uploadedfile', 
            data: { },

            autoSubmit: false,
                         
            onSubmit: function(file, ext){
            	  
				$('#errorMessageDiv').empty();
            	this.setData ( {'author'   : '<?= $_SESSION['username']?>',
                	            'ownerURI' : $('#curQuestionId').val()});  
            	this.disable();
           },  
           onComplete: function(file, response){

	            if(response === "success"){  
	            	var counter = $('#counter').val();
	   	    		//changes the question if it is not last
	   	    		if ( counter <= questions.length - 2 ){
	   	    			counter++;
	   	    			$('#counter').val(counter);
	   	    			$('#curQuestionImg').attr('src', questions[counter]);
	   	
	   	    			//$('#curQuestionId').val(questions[counter]);
	   	    			$('#curQuestionId').val(questionsURI[counter]);
	   	            	this.enable();
	   	     			// increment the current number of the question
	   	     			curQuestionNum++;
	   	     			//if (curQuestionNum <= numQuestion) {
	   	     			$('#curQuestionNumDiv').html('<h2> Question ' + curQuestionNum + '/' + numQuestion + '</h2>');
	   	     			//}
	   	     	   	            	
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
    	            par.appendTo('#errorMessageDiv');
    	           	this.enable();
	            }
 	              
           }  
       });  
       $('#curQuestionImg').attr('src', questions[0]);
       $('#curQuestionId').val(questionsURI[0]);

       $('#greetingDiv').html('<?= $greetingMsg?>');
	   $('#curQuestionNumDiv').html('<h2> Question ' + curQuestionNum + '/' + numQuestion + '</h2>');
            
    });

</script>

<style type='text/css'>

	#uploadSolutionDiv{
		width: 100%;
		height: 100%;
	}
	
	#greetingDiv {
		width: 100%;
		margin: 2% 0 0 4%; 
		font-size: 18px;
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
	
	#uploadButtonDiv {  
        margin:30px 70px; padding:15px;  
        font-weight:bold; font-size:1.3em;  
        font-family:Arial, Helvetica, sans-serif;  
        text-align:center;  
        background:#f2f2f2;  
        color:#3366cc;  
        border:1px solid #ccc;  
        width:150px;  
        cursor:pointer !important;  
        -moz-border-radius:5px; -webkit-border-radius:5px;  
    }  
	
	/* 
	We can't use ":hover" preudo-class because we have
	invisible file input above, so we have to simulate
	hover effect with javascript. 
	 */
	#uploadButtonDiv.hover {
		background: url(button.png) 0 56px;
		color: #95A226;
		cursor:pointer !important;  
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
		
		</br></br></br></br>
          <label><p>
            Click <strong>Browse...</strong> to upload your solution: <br/></p>
            
			<input type="hidden" id="curQuestionId" name="curQuestionId" value="" />
			<input type='hidden' id='counter' name='counter' value="0"/>
          	<div id="uploadButtonDiv">Browse</div>
          	<div id='errorMessageDiv'></div>
	</div>
</div>

<?php 

require_once './footer.php';

?>
