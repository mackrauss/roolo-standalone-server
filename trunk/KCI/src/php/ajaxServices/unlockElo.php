<?php
session_start(); 

require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Lock.php';

$ownerUri = $_REQUEST['ownerUri'];

$roolo = new RooloClient();

$query = "type:Lock AND owneruri:".$roolo->escapeSearchTerm($ownerUri);
echo $query;
$locks = $roolo->search($query, 'metadata', 'latest');
print_r($locks);
if (sizeof($locks) != 0){
	foreach($locks as $lock){
		$lockUri = $lock->get_uri();
		
		$roolo->deleteElo($lockUri);
	}
	
}