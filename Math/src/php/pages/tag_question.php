<?php
session_start();
$_SESSION["username"] = $_GET["username"];
require_once './header.php';

$questions = glob(dirname(__FILE__) . '/../../../Questions/*');
$questions = array_filter($questions, 'is_file');

for ($i=0; $i<sizeof($questions); $i++){
	$curPath = $questions[$i];
	$curPath = substr($curPath, strrpos($curPath, '/Questions'));
	$questions[$i] = $curPath;
}

?>

<!--<script type='text/javascript' src="/src/js/highslide-full.packed.js"></script>-->
<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>


<script type='text/javascript'>

	$(document).ready(function(){

		$('.categoryChoice').change(function () {
		    if($(this).is(':checked')) {
		        $(this).attr('checked','checked');
		    }else {
		    	$(this).removeAttr('checked');
			}
			
		});

		
	});

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

	<div id='imgDiv'>
		<img id='curQuestion' src="<?= $questions[0]?>"/>
	</div>
	
	<div id='categoryDiv'>
		Please choose the category that this question fits best <br/><br/><br/>
		<form action="">
			<input class='categoryChoice' type="checkbox" name="category" value="Geometry"> Geometry <br/> <br/>
			<input class='categoryChoice' type="checkbox" name="category" value="Trigonametry"> Trigonametry <br/> <br/>
			<input class='categoryChoice' type="checkbox" name="category" value="Exponential"> Exponential <br/> <br/>
			<input class='categoryChoice' type="checkbox" name="category" value="Algebra"> Algebra <br/> <br/>
			
			<input type="button" value="Submit"/>
		</form>
	</div>

<!--		<input type='button' value='clickMe' onClick='tagQuestion();'/>-->

</div>


<!--<script type='text/javascript'>-->
<!---->
<!--	function tagQuestion (){-->
<!---->
<!--		var saveData = $('input.categoryChoice:checked').serialize();-->
<!--		-->
<!--		$.get("/src/php/ajaxServices/tagQuestion.php", { username: '<?= $_SESSION['username']?>',  categories: saveData},-->
<!--		  	function(data){-->
<!--			}-->
<!--		);-->
<!--	}-->
<!---->
<!--</script>-->





<?php 


require_once './footer.php';









