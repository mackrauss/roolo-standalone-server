<?php
session_start();

?>
<html>
	<head>
		<script type="text/javascript" src="/src/js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="/src/js/jquery-ui-1.7.2.custom.min.js"></script>
		
<!--		<link rel="stylesheet" type="text/css" href="/src/css/main.css" />-->
		<link rel="stylesheet" type="text/css" href="/src/css/style-michelle.css" />
		<link rel="stylesheet" type="text/css" href="/src/css/ui-lightness/jquery-ui-1.7.2.custom.css" />
	</head>

	<body class="oneColElsCtrHdr">
		<div id="header">
			<div id="logo" onClick="document.location.href='/src/php/pages/index.php'">
		    </div>
		    <div id='signout' onClick="document.location.href='/src/php/ajaxServices/logout.php'" style='cursor:pointer; color:white; margin-left:55%; padding-top: 30px; display: none'> Sign Out </div>    
		    <div id="timer" style='display: none'>
		    	<p>TIME REMAINING</p>
		    	<h1 id='timerValue'></h1>
		  	</div>
	  </div>
	  <!-- end #header -->

		<div id="container">  