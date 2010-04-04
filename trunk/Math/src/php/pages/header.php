<?php
session_start();

?>
<html>
	<head>
		<script type="text/javascript" src="/src/js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="/src/js/jquery-ui-1.7.2.custom.min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="/src/css/main.css" />
		<link rel="stylesheet" type="text/css" href="/src/css/ui-lightness/jquery-ui-1.7.2.custom.css" />
	</head>

	<body>
		<div id='header' style='margin-top: 30px; margin-left: auto; margin-right: auto; width: 900px; height: 100px;'>
			<div style='float: left; width: 50%'>
				<font size='5' color='blue'> UTS M3 Science </font><br/>
				<font size='9' onClick="document.location.href='/src/php/pages/tag_question.php?username=<?= $_SESSION['username']?>'" style='cursor:pointer;'> Mathematics </font> 
			</div>
			<div style='float: right; width: 40%;'>
				<div id='signout' onClick="document.location.href='/src/php/ajaxServices/logout.php'" style='cursor:pointer; font-family: verdana; font-size: small; text-decoration: underline; color:white; text-align: right; padding-top: 5px; display: none'>Sign Out</div>
			</div>
			
		</div>

		<div id='main' style='min-height: 400px;'> 