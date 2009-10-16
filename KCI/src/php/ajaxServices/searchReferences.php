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

$rooloQuery = "type:Tag AND ownertype:Reference AND title:($queryTagPiece)";
$matchingTags = $roolo->search($rooloQuery, 'metadata', 'latest');



echo "<pre>$rooloQuery</pre>";
?>
