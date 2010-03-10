<?php

require_once './header.php';
require_once '../RooloClient.php';

error_reporting(E_STRICT);

//if(isset($_SESSION['multiple_choice'])){
//	$_SESSION['multiple_choice'] = "";
//    $_SESSION['msg'] =  "";
//	$_SESSION['username']= "";
//}

if(empty($_SESSION['msg'])){
    $_SESSION['msg'] =  "Please Sign In! Username & Password Are Case Sensitive!";
    $_SESSION['username']= "";
}
?>

<script type='text/javascript' src="/src/js/jquery.corner.js"/></script>

<style type='text/css'>


	#subMsgDiv {
		width: 100%;
		margin: 10% 0% 0 27%; 
		font-size: 14px;
		float: left;
		margin-bottom: 20px;
	}
	#mainDiv {
		width: 100%;
		text-align: left; 
		margin-left: 25%;
	}
	
	#loginDiv {
		width: 100%;
	}

	.label {
	    font-weight: bold;
	    margin-right: 4px;
	    color: black;
	    float: left;
	    width: 15%;
	    text-align: right;
    }
    
    .box {
    	margin-left: 1%;
    }
    
    #submitDiv {
    	float: left;
    	margin-left: 28%;
   } 	
	
</style>

<div id='subMsgDiv'><?= $_SESSION['msg']?></div>

<div id='mainDiv'>

	<form action="/src/php/ajaxServices/securityCheck.php" method="post">
		<div class="label" ><u>U</u>sername: </div>
		<div class='box'> <input type="text" name="username" size="30" value='<?= $_SESSION['username'] ?>'></div><br/>
		<div class="label"><u>P</u>assword: </div>
		<div class='box'><input type="password" name="password" size="30"></div><br/>
	
		<div id='submitDiv'>
			<input class='btn' type="submit" style='margin-left: 30px' value='Sign In' ><br/>
		</div>
	</form>	

</div>
<?php 
require_once './footer.php';
?>