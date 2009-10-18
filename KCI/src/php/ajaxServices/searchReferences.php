<?php
session_start();

require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Reference.php';

$query = $_REQUEST['query'];
$tags = explode(',', $query);

$queryTagPiece = '';
$roolo = new RooloClient();

for ($i=0;$i<sizeof($tags);$i++){
	$tag = $tags[$i];
	$tag = trim($tag);
	
	$queryTagPiece .= '"'.$roolo->escapeSearchTerm($tag).'"';
	
	if ($i != sizeof($tags)-1){
		$queryTagPiece .= ' OR ';
	}
}

// TODO: add status:active to the query
$rooloQuery = "type:Tag AND ownertype:Reference AND title:($queryTagPiece)";
$matchingTags = $roolo->search($rooloQuery, 'metadata', 'latest');

echo "<pre>";

/*
 * Find all the references that these tags are pointing to
 */
$matchingReferenceUris = array();
foreach($matchingTags as $matchingTag){
	$matchingReferenceUris[$matchingTag->get_ownerUri()] = 1;
}

$matchingReferenceUris = array_keys($matchingReferenceUris);
$matchingReferences = array();
foreach($matchingReferenceUris as $curReferenceUri){
	$results = $roolo->search('type:Reference AND uri:'.$roolo->escapeSearchTerm($curReferenceUri), 'metadata', 'latest');
	$matchingReferences[] = $results[0];
}

//print_r($matchingReferenceUris);
//print_r($matchingReferences);

echo generateReferenceSearchResults($matchingReferences);
echo "</pre>";

function generateReferenceSearchResults($references){
	$o = '';
	
	foreach($references as $curReference){
		$curReferenceUri = $curReference->get_uri();
		$curReferenceTitle = $curReference->get_title();
		$curReferenceId = $curReference->get_id();
		
		$o .= '<div>';
		$o .= "<img src='/src/images/add.png' onClick='addReferenceToArticle(\"$curReferenceUri\")' /> ";
		$o .= "<a href='/src/php/referencePage.php?id=$curReferenceId' target='_blank'>$curReferenceTitle</a>";
		$o .= "</div><br/>\n";
	}
	
	return $o;
}




















