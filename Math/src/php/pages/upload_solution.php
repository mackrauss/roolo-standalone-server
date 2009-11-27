<?php
require_once './header.php';

require_once '../RooloClient.php';
require_once '../dataModels/UploadedSolution.php';

$answers_dir = '../../../Answers';


// check username variable has been sent
if(isset($_GET['username'])){
	$_SESSION['username'] = $_GET['username'];
}else {
	echo 'username has not been set!!!!!!!!!!!!!';
}
// check this has been taged by teacher or student
if(isset($_GET['masterSolution'])){
	$_SESSION['masterSolution'] = $_GET['masterSolution'];
}else { // zero means false and one means true
	$_SESSION['masterSolution'] = 0;
}

echo "<h2>" . $_SESSION['username'] . "</h2>";

$questions = glob(dirname(__FILE__) . '/../../../Questions/*');
$questions = array_filter($questions, 'is_file');
$counter=0;
for ($i=0; $i<sizeof($questions); $i++){
	$curPath = $questions[$i];
	$questions[$i] = basename($curPath);
}

?>

<!--<script type='text/javascript' src="/src/js/highslide-full.packed.js"></script>-->
<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>


<script type='text/javascript'>

	// an array that keeps all questions path
	var questions = new Array('<?= implode('\', \'', $questions)?>');

</script>
<script type='text/javascript'>
    $(document).ready(function(){
        $('#curQuestionImg').attr('src', '/Questions/'+questions[0]);
        $('#curQuestionId').val(questions[0]);
    });

	function nextQuestion(){

		//changes the question if it is not last
		if ( counter <= questions.length - 2 ){
			counter++;
			$('#counter').val(counter);
			$('#curQuestionImg').attr('src', '/Questions/'+questions[counter]);
            $('#curQuestionId').val(questions[counter]);

			//enable the submit button
			$('#submit').removeAttr('disabled');
				
		}
		else{
			alert ("please Wait to find your group!");
		}
			
	}
		
</script>

<style type='text/css'>

	div#tagQuestionDiv{
		width: 100%;
		height: 100%;
	}

	#imgDiv {
		width: 40%;
		height: 150px;
		float: left;
	}
	
	#categoryDiv {
		width: 40%;
		float: left;
		margin-left: 10%;
	
	}

</style>

<div id='tagQuestionDiv'>

  <?php
    if ($_POST) {

      $rooloClient = new RooloClient();

      
      $solution_file = $_FILES['solution_file'];
      $solution_file_dest = $answers_dir."/".$_POST['curQuestionId'];
      move_uploaded_file($solution_file['tmp_name'], $solution_file_dest);

      $solution = new UploadedSolution();
      $solution->set_title($_POST['curQuestionId']);
      $solution->set_author("mzukowski");
      $solution->set_version('');
      $solution->set_datecreated('');
      $solution->set_uri('');
      $solution->setContent("");
      
echo "<pre>";
print_r(htmlentities($solution->toString()));
      $response = $rooloClient->addElo($solution);
      print_r($response);
      $solution = new UploadedSolution($response);
      
      print_r($solution);
      echo "</pre>";
    }
  ?>

	<div id='imgDiv'>
		<img id='curQuestionImg' src=""/>
	</div>
	
	<div id='categoryDiv'>
		<form action="" enctype="multipart/form-data" method="POST">
          <label>
            Click <strong>Browse...</strong> to upload your solution: <br/>
            <input type="file" id="solution_file" name="solution_file" />
          </label>
          <input type="hidden" id="curQuestionId" name="curQuestionId" value="" />
		  <input type="submit" id='submit' value="Submit" />
          <input type='hidden' id='counter' name='counter' value="0"/>
		</form>
	</div>

</div>

<?php 

require_once './footer.php';









