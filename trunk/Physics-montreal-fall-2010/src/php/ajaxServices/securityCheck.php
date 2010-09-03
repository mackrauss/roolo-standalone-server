<?php
session_start();

$_SESSION['username'] = $_POST['username'];
$_SESSION['password'] = $_POST['password'];
$_SESSION['runId'] = $_POST['runId'];

$url = "http://localhost:8070/webapp/j_acegi_security_check";
//$url = "http://localhost:8080/webapp/j_acegi_security_check";
//$url = "http://iitp.dawsoncollege.qc.ca:8080/webapp/j_acegi_security_check"; 

$msg = "The username or password you entered is incorrect.";

// create a new cURL resource
//$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_HEADER, 1);   // TRUE to include the header in the output.
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
//curl_setopt($ch, CURLOPT_POSTFIELDS, "j_username=".$_SESSION['username']."&j_password=".$_SESSION['password']);
//
//// grab URL and pass it to the browser
//$result = curl_exec($ch);
//curl_close($ch);
//
//$notMember = strstr($result, "failed=true") || trim($result) == '';

$notMember = false;

if($notMember){
	$_SESSION['msg'] = $msg;
	header("Location:/src/php/pages/");
}else{
	$_SESSION['loggedIn'] = TRUE;
//	header("Location:/src/php/pages/runAuthoring.php");
	if (strstr($_SESSION['runId'], "v1-c1")){
		if (strstr($_SESSION['username'], "teacher")){
			//should go to special page for teachers
			header("Location:/src/php/pages/teacherView.php");
		}else if (strstr($_SESSION['username'], "physicsgroup")){
			//should go to special page login with group name
			/*
			 * TODO change the name to version2D.php
			 */
			header("Location:/src/php/pages/version2B.php");
		}else {
			header("Location:/src/php/pages/version2B.php");
		}
	}else {
		header("Location:/src/php/pages/");
	}
}
?>