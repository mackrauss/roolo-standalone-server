<?php
session_start(); 

require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Link.php';

$refUri = $_REQUEST['refUri'];
$articleUri = $_REQUEST['articleUri'];

$roolo = new RooloClient();

/*
 * Find the link that is attaching the ref and article and delete it
 */
$refUriEscaped = $roolo->escapeSearchTerm($refUri);
$articleUriEscaped = $roolo->escapeSearchTerm($articleUri);

$query = "type:Link AND uri1:$articleUriEscaped AND uri2:$refUriEscaped";
$results = $roolo->search($query, 'metadata', 'latest');

if (sizeof($results) != 1){
	echo "An error occured while removing the reference from your article. Please try refrshing the page and trying again";
	die();
}else{
	$link = $results[0];
	$roolo->deleteElo($link->get_uri());
	echo "SUCCESS";
}

