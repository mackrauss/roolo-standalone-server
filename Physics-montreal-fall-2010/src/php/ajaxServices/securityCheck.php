<?php
session_start();

require_once '../Application.php';

$_SESSION['username'] = $_REQUEST['username'];
$_SESSION['password'] = $_REQUEST['password'];
$_SESSION['runId'] = $_REQUEST['runId'];
$runId = $_REQUEST['runId'];

$url = "http://localhost:8070/webapp/j_acegi_security_check";
//$url = "http://localhost:8080/webapp/j_acegi_security_check";
//$url = "http://iitp.dawsoncollege.qc.ca:8080/webapp/j_acegi_security_check"; 

/*
 * Check with the portal if the user credentials are valid
 */
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

/*
 * Check with Application.php if user credentials are valid
 */
$notMember = true;
$memberType = '';

// check if this is a valid user
$username = $_SESSION['username'];
if (isset(Application::$users[$username])){
	$realPassword = Application::$users[$username]['password'];
	if ($realPassword == $_SESSION['password']){
		$notMember = false;
		$memberType = 'user';
	}
}

// if not recorgnized as a user, check if this is a valid group
if ($notMember){
	if (isset(Application::$userGroups[$username])){
		$realPassword = Application::$userGroups[$username]['password'];
		if ($realPassword == $_SESSION['password']){
			$notMember = false;
			$memberType = 'group';
		}
	}	
}

// if not recognized yet, check if this is a valid admin
if ($notMember){
	if (isset(Application::$admins[$username])){
		$realPassword = Application::$admins[$username]['password'];
		if ($realPassword == $_SESSION['password']){
			$notMember = false;
			$memberType = 'admin';
		}
	}	
}


$logUserIn = false;
$forward = '';

if ($memberType == 'user'){
	if (isRunIdValid($runId)){
		$logUserIn = true;
		$forward = '/src/php/pages/singleStudentAnswering.php';
	}
	
}elseif ($memberType == 'group'){
	if (isRunIdValid($runId)){
		$logUserIn = true;
		$forward = '/src/php/pages/groupAnswering.php';
	}
	
}elseif ($memberType == 'admin'){
	$logUserIn = true;
	$forward = '/src/php/pages/runAuthoring.php';
}


if ($logUserIn){
	logInUserSession($memberType);
	header("Location:$forward");
}else{
	header("Location:/src/php/pages/");
}


function isRunIdValid($runId){
	return $runId !== null && $runId !== "" && strlen($runId) == 5;
}

function logInUserSession($memberType){
	$_SESSION['loggedIn'] = TRUE;
	$_SESSION['memberType'] = $memberType;
}