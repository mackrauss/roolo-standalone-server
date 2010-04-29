<?php

require_once './header.php';
require_once '../RooloClient.php';
require_once '../graphML/GraphML.php';

error_reporting(E_STRICT);

//if(isset($_SESSION['multiple_choice'])){
//	$_SESSION['multiple_choice'] = "";
//    $_SESSION['msg'] =  "";
//	$_SESSION['username']= "";
//}

if(empty($_SESSION['msg'])){
    $_SESSION['msg'] =  "Please Login! Username & Password Are Case Sensitive!";
    $_SESSION['username']= "";
}

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
		margin-left: 28%;
		margin-top: 50px;
	}
</style>

<div id="container">
	<div id="logo"></div>  
	<div id="login">
		<form id="loginForm" name="loginForm" action="/src/php/ajaxServices/securityCheck.php" method="post">
			<div id='subMsgDiv'><?= $_SESSION['msg']?></div>
			<label>Username</label><input name="username" type="text" value='<?= $_SESSION['username'] ?>'><br/>
			<label>Password</label><input name="password" type="password" /><br/>
			<input name="submit" type="submit" value="LOGIN" class="btn" />
		</form>	
	</div>
</div>

<div id='instructions'>
	<fieldset style='border: 3px solid #669900'>
		<legend style='color: black; font-size: 18px;'>Instructions</legend>
		<p> Please use one of the following user/pass to login </p>
	
		<ul>
			<li>student111/dawson</li>
			<li>student211/dawson</li>
			<li>student311/dawson</li>
		</ul>
		
		<p> If you notice that when you log in, there are no more questions to be answered, just click <a href='./resetRoolo.php'>Reset</a> and wait a little bit.</p>
	</fieldset>

</div>
<?php 
require_once './footer.php';
?>