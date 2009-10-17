<?php
session_start();
require_once './header.php';
require_once dirname(__FILE__).'/dataModels/PersonalReflection.php';
require_once dirname(__FILE__).'/RooloClient.php';
$username = $_SESSION['username'];

$rooloClient = new RooloClient();
echo "<h2> Welcome " . $username . "</h2>";

?>

<script type='text/javascript' src='../../library/js/jquery-1.3.2.min.js'></script>
<script type='text/javascript' src='../../library/js/jquery-ui-1.7.2.custom.min.js'></script>
<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
<title>Personal Reflection</title>

<style>
	#buttonArea {
 		width:90%;
		margin-right: 20px;	
		padding:10px 0px 40px 10px; 
 	}
 	
 	.BigButton{
 		font-size: 16px;
 		width: 250px;
 		height: 40px;
 		margin-bottom: 2%;
 	}

</style>

<script type='text/javascript'>

	sectionIds = new Array('<?= implode(array_keys(PersonalReflection::getSections()), "','") ?>');

	function saveReflection(reflectionUri){
		
		// Adding all the params (title, all sections ...etc) to the array to be sent via ajax
		params = {'action': 'saveReflection', 'reflectionUri': reflectionUri};
		params ['title'] = $('#reflectionTitle').val();
		for (index in sectionIds){
			curSection = sectionIds[index];
			params[curSection] = $("#"+curSection).val(); 
		}
		
		$.post('/src/php/ajaxServices/createPersonalReflection.php', params, 
				function(data){
					$('#msgDiv').html(data);
				}
		);

		$('#newReflection').show();
	}


	function createReflectionTemplate(action, reflectionUri){

		$('#newReflection').hide();
		$.post('/src/php/ajaxServices/createPersonalReflection.php', {'action': action, 'reflectionUri': reflectionUri}, 
				function(data){
					$('#curReflection').html(data);
				}
		);

		
	}

	function loadReflection(reflectionUri){
		console.log(reflectionUri);
		$.post('/src/php/ajaxServices/createPersonalReflection.php', {'action': 'loadReflection', 'reflectionUri': reflectionUri}, 
				function(data){
					console.log(data);
					$('#curReflection').html(data);
				}
		);
		return false;
	}
</script>

<div id='msgDiv'>

</div>


<h3 style='margin-top: 5%'> Your Previous Reflections </h3>
<div id='previousReflections'>

	<?php 
	
		$query = "type:PersonalReflection AND author:" . $username;
		$results = $rooloClient->search($query, 'metadata');
		foreach ($results as $reflection){
			echo "<a href='#' onClick='return loadReflection(\"" . $reflection->get_uri(). "\");'>" . $reflection->get_title() . "</a><br/>";
		}
	
	
	?>
</div>

<div id='buttonArea'>
	<input id='newReflection' type='button' class='BigButton' onClick="createReflectionTemplate('newReflection', null)" style='float:right' value='Start a new persoanl reflection'>
</div>

<div id='curReflection'>

</div>

<br/>
<br/>
<br/>