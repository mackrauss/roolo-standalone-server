<?php
session_start();
 
require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Section.php';

$sectionUri = $_REQUEST['sectionUri'];
$sectionCode = $_REQUEST['sectionCode'];
$articleUri = $_REQUEST['articleUri'];
$sectionContent = $_REQUEST['sectionContent'];

$section = new Section();
$section->set_uri($sectionUri);
$section->set_author($_SESSION['username']);
$section->set_datecreated('');
$section->set_datelastmodified('');
$section->set_title($sectionCode);
$section->set_ownerType('Article');
$section->set_ownerUri($articleUri);
$section->setContent($sectionContent);

$roolo = new RooloClient();
$response = $roolo->updateElo($section);

echo $sectionUri . " <> " . $sectionCode . " <> " . $articleUri . " <> " . $sectionContent;
echo $response;
?>