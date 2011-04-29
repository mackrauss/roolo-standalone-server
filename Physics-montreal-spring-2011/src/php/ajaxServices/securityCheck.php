<?php
session_start();

require_once '../Application.php';

$userToken = $_REQUEST['token'];

//$userSession = json_decode(file_get_contents("http://rollcall.proto.encorelab.org/sessions/validate_token.json?dataType=json&token=$userToken"));
$userSession = json_decode(file_get_contents("http://dalite.oise.utoronto.ca:9999/sessions/validate_token.json?dataType=json&token=$userToken"));

if (isset($_REQUEST['runId'])){
	$_SESSION['runId'] = $_REQUEST['runId'];	
}

$username = $userSession->session->user->username;
$userRole = strtolower($userSession->session->user->kind);

$forward = '';
$memberType = '';
if ($userRole == 'instructor' || $userRole == 'admin'){
	$memberType = 'admin';
	$forward = '/src/php/pages/runAuthoring.php';
}else{
	$memberType = 'user';
	$forward = '/src/php/pages/tar.php';	
}

logInUserSession($username, $memberType);

header("Location:$forward");

function logInUserSession($username, $memberType){
	$_SESSION['loggedIn'] = TRUE;
	$_SESSION['memberType'] = $memberType;
	$_SESSION['username'] = $username;
}