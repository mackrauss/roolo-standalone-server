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
		<div style='margin-top: 4%; margin-left:20%'>
			<font size='5' color='blue'> UTS M3 Science </font><br/>
			<font size='9' onClick="document.location.href='/src/php/pages/tag_question.php?username=<?= $_SESSION['username']?>'" style='cursor:pointer;'> Physics </font> 
		</div>

		<div id='main' style='height: 70%'> 