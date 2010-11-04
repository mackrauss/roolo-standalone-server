<?php
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['memberType'] != 'admin') {
	header("Location:/src/php/pages/");
}

date_default_timezone_set('America/New_York');
?>
<html>
	<head>
		<script type="text/javascript" src="/src/js/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="/src/js/jquery-ui-1.7.2.custom.min.js"></script>
		
<!--		<link rel="stylesheet" type="text/css" href="/src/css/main.css" />-->
		<link rel="stylesheet" type="text/css" href="/src/css/style-michelle.css" />
<!--		<link rel="stylesheet" type="text/css" href="/src/css/ui-lightness/jquery-ui-1.7.2.custom.css" />-->
	</head>

	<body class="oneColElsCtrHdr">
	<div id="topContainer">
	<div id="headerContainer">
		<div id="header">
				<?php 
					if ($_SESSION['loggedIn']){
				?>
						<div id="signOut" onClick="document.location.href='/src/php/ajaxServices/logout.php'"> Signed in as <b> <?= $_SESSION['username'] ?> </b> | <a href="/src/php/ajaxServices/logout.php"> Sign out</a></div>
				<?php 
					}
				?>
		</div>
<!--		    <div id='signout' onClick="document.location.href='/src/php/ajaxServices/logout.php'" style='cursor:pointer; color:#1E7EC8; margin-left:68%; padding-top: 5px; display: none'><b> Log Out</b></div>    -->
	</div>
	  <!-- end #header -->

