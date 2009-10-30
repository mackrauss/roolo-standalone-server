<?php
session_start(); 

require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Lock.php';

$uri = $_REQUEST['uri'];
$roolo = new RooloClient();

$section = $roolo->retrieveElo($uri);
if (get_class($section) == 'Section'){
	echo $section->getContent();
}
