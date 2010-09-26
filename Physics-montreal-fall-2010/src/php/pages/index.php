<?php

require_once './header.php';
require_once '../RooloClient.php';
require_once '../graphmlServices/GraphML.php';

error_reporting(E_STRICT);

//if(isset($_SESSION['multiple_choice'])){
//	$_SESSION['multiple_choice'] = "";
//    $_SESSION['msg'] =  "";
//	$_SESSION['username']= "";
//}

if(empty($_SESSION['msg'])){
    $_SESSION['username']= "";
    $_SESSION['runId']= "";
}

$roolo = new RooloClient();
$runConfigs = $roolo->search('type:RunConfig AND runpublished: 1', 'metadata', 'latest');


//if (!file_exists('../graphML/classroom.graphml')){
//	$graphML = new GraphML();
//	//creates Graph XML file with header and footer
//	$graphML->createEmptyGraphFile();
//}
?>

<style type='text/css'>
	#instructions {
		width: 380px;
		height: 140px;
		float: left;
		text-align: left; 
		margin-left: 70%;
		margin-top: -400px;
	}
</style>

<div id="container">
	<div id="logo"></div>  
	<div id="login">
		<form id="loginForm" name="loginForm" action="/src/php/ajaxServices/securityCheck.php" method="post">
			<label>Run</label><!-- <input name="runId" type="text" value='<?= $_SESSION['runId'] ?>'/>-->
			<select name='runId' id='runId'>
				<option value=''>Select a run</option>
			<?php 
			foreach ($runConfigs as $curRunConfig){
				$curRunId = $curRunConfig->runid;
				echo "<option value='$curRunId'>$curRunId</option>";
			}
			?>
			</select>
			<br/>
			<label>Username</label><input name="username" type="text" value='<?= $_SESSION['username'] ?>'><br/>
			<label>Password</label><input name="password" type="password" /><br/>
			<input name="submit" type="submit" value="LOGIN" class="btn" />
		</form>	
	</div>
</div>

<!--<div id='instructions'>-->
<!--	<fieldset style='border: 3px solid #6699CC'>-->
<!--		<legend style='color: black; font-size: 18px;'>Instructions</legend>-->
<!--		<p> Please use one of the following user/pass to login </p>-->
<!--	-->
<!--		<ul>-->
<!--			<li>student111/dawson</li>-->
<!--			<li>student211/dawson</li>-->
<!--			<li>student311/dawson</li>-->
<!--		</ul>-->
<!--		-->
<!--		<p> If you notice that when you log in, there are no more questions to be answered, just click <a href='./resetRoolo.php'>Reset</a> and wait a little bit.</p>-->
<!--	</fieldset>-->
<!---->
<!--</div>-->
<?php 
require_once './footer.php';
?>