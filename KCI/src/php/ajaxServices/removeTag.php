<?php
session_start();

require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Tag.php';

$tagUri = $_REQUEST['tagUri'];

$roolo = new RooloClient();

$tag = $roolo->retrieveElo($tagUri);
$tag->set_datedeleted('');
$tag->set_status('deleted');

$response = $roolo->updateElo($tag);
echo $response;

?>