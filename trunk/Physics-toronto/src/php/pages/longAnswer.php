<?php
session_start();
error_reporting(E_STRICT);

if (!$_SESSION['loggedIn'] || !isset($_SESSION['loggedIn'])){
	header("Location:/src/php/pages/");
}

require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Solution.php';
?>




<?php 
require_once './footer.php';
?>