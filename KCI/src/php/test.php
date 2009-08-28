<?php 

require_once '../../domLib/simple_html_dom.php';
require_once 'dataModels/XMLSupported.php';
require_once 'dataModels/Elo.php';
require_once 'dataModels/Proposal.php';
require_once 'dataModels/Comment.php';
require_once 'RooloClient.php';

$results = file_get_contents('http://localhost:8080/roolo-standalone-server/RetrieveAll');


$rooloClient = new RooloClient();
echo $rooloClient->retrieveAll(array('issue'));




?>

