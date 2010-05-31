<?php
session_start();

$_SESSION['username'] = $_POST['username'];
$_SESSION['password'] = $_POST['password'];

$url = "http://localhost:8070/webapp/j_acegi_security_check";
//$url = "http://localhost:8080/webapp/j_acegi_security_check";
//$url = "http://iitp.dawsoncollege.qc.ca:8080/webapp/j_acegi_security_check"; 
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

if($notMember){
	$_SESSION['msg'] = $msg;
	header("Location:/src/php/pages/");
}else{
	$_SESSION['loggedIn'] = TRUE;
	if (strstr($_SESSION['username'], "physicsGroup")){
		//should go to special page login with group name
		header("Location:/src/php/pages/step2.php");
	}else{
		header("Location:/src/php/pages/step1.php");
	}
}
?>