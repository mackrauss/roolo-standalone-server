<?php
session_start(); 

require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Tag.php';

$tagStr = $_REQUEST['tag'];
if (trim($tagStr) == ''){
	die();
}
$tags = explode(',', $tagStr);
$ownerType = $_REQUEST['ownerType'];
$ownerUri = $_REQUEST['ownerUri'];

$roolo = new RooloClient();

$finalResponse = '';
foreach($tags as $curTagStr){
	/*
	 * Make sure it's not a duplicate tag
	 */
	$curTagStr = trim($curTagStr);
	$ownerUriForSearch = $roolo->escapeUri($ownerUri);
	$query = "type:Tag AND owneruri:$ownerUriForSearch AND title:$curTagStr AND status:active";
	$results = $roolo->search($query, 'metadata', 'latest');
	if (sizeof($results) != 0){
		continue; 
	}
	
	$tag = new Tag();
	$tag->set_uri('');
	$tag->set_author($_SESSION['username']);
	$tag->set_datecreated('');
	$tag->set_datelastmodified('');
	$tag->set_datedeleted('');
	$tag->set_title($curTagStr);
	$tag->set_ownerType($ownerType);
	$tag->set_ownerUri($ownerUri);
	$tag->set_status('active');
	$tag->setContent('');
	
	
	$response = $roolo->addElo($tag);
	$savedTag = new Tag($response);
	
	$finalResponse .= Tag::generateHtml($savedTag->get_uri(), $savedTag->get_title());
}

echo $finalResponse;
?>