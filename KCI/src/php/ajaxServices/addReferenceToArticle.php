<?php
session_start(); 

require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Link.php';
require_once dirname(__FILE__).'/../dataModels/Reference.php';
require_once dirname(__FILE__).'/../util/ReferenceUtil.php';

$articleUri = $_REQUEST['articleUri'];
$refUri = $_REQUEST['refUri'];

$roolo = new RooloClient();
/*
 * Make sure it's not a duplicate
 */
$articleUriEscaped = $roolo->escapeSearchTerm($articleUri);
$refUriEscaped = $roolo->escapeSearchTerm($refUri);
$query = "type:Link AND type1:Article AND type2:Reference AND uri1:$articleUriEscaped AND uri2:$refUriEscaped";
$results = $roolo->search($query, 'metadata', 'latest');
if (sizeof($results) != 0){
	echo "DUPLICATE";
	die();
}

/*
 * Create link object and save it
 */
$link = new Link();
$link->set_uri('');
$link->set_author($_SESSION['username']);
$link->set_datecreated('');
$link->set_datelastmodified('');
$link->set_datedeleted('');
$link->set_type1('Article');
$link->set_type2('Reference');
$link->set_uri1($articleUri);
$link->set_uri2($refUri);
$link->set_title('');
$link->setContent('');

$response = $roolo->addElo($link);
$savedLink = new Link($response);

/*
 * Retrieve Reference and return the generated div
 */
$results = $roolo->search('type:Reference AND uri:'.$refUriEscaped, 'elo', 'latest');
$reference = $results[0];

echo generateAttachedReference($reference, $savedLink);














