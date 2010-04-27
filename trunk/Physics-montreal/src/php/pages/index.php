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
<?php 
require_once './footer.php';
?>