<?php
session_start();

$_SESSION['username'] = $_REQUEST['username'];
$_SESSION['password'] = $_REQUEST['password'];

$url = "http://localhost:8070/webapp/j_acegi_security_check";
//$url = "http://192.168.1.102:8070/webapp/j_acegi_security_check";
//$url = "http://142.150.101.159:8070/webapp/j_acegi_security_check";
//$url = "http://localhost:8080/webapp/j_acegi_security_check";
$msg = "The username or password you entered is incorrect.";

// create a new cURL resource
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);   // TRUE to include the header in the output.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
curl_setopt($ch, CURLOPT_POSTFIELDS, "j_username=".$_SESSION['username']."&j_password=".$_SESSION['password']);

// grab URL and pass it to the browser
$result = curl_exec($ch);
curl_close($ch);

$notMember = strstr($result, "failed=true");
//TODO
// chek if server is down send suitable message
//if (strstr($result,"Failed to load source" )){
//	$_SESSION['msg'] = "Server is Down!";
//	header("Location:/src/php/pages/");
//}

if($notMember){
	$_SESSION['msg'] = $msg;
	header("Location:/src/php/pages/");
}else{
	$_SESSION['loggedIn'] = TRUE;
	if (strstr($_SESSION['username'], "group")){
		header('Location:/src/php/pages/groupHomePage.php');
	}else{
		header('Location:/src/php/pages/tag_question_yes_no.php?username=' . $_SESSION['username']);
	}
}

?>