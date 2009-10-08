<?php
session_start();
$_SESSION['username'] = 'Ali Ajellu';
?>
<html>
	<head>
		<script type="text/javascript" src="/src/js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="/src/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
	
		<link rel="stylesheet" type="text/css" href="/src/css/main.css" />
	</head>

	<body>
		<div style='margin-top: 4%; margin-left:20%'>
			<font size='5' color='blue'> UTS M3 Science </font><br/>
			<font size='9' onclick="document.location.href='/src/php/index.php'"> Climage Change </font> 
		</div>

		<div id='main'>