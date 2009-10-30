<?php
session_start(); 

require_once dirname(__FILE__).'/../RooloClient.php';

$targetUri = $_REQUEST['uri'];
$roolo = new RooloClient();

$query = "type:Lock AND owneruri:".$roolo->escapeSearchTerm($targetUri);
$locks = $roolo->search($query, 'metadata', 'latest');

if (sizeof($locks) == 0){
	echo "{isLocked: 0}";
}else{
	$lock = $locks[0];
	echo "{isLocked: 1, lockedBy: '".$lock->get_author()."'}";
}