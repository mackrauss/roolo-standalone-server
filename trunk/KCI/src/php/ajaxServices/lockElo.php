<?php
session_start(); 

require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Lock.php';

$ownerUri = $_REQUEST['ownerUri'];
$ownerType   = $_REQUEST['ownerType'];

$roolo = new RooloClient();

$lock = new Lock();
$lock->set_uri('');
$lock->set_author($_SESSION['username']);
$lock->set_datecreated('');
$lock->set_datelastmodified('');
$lock->set_title('');
$lock->set_ownerType($ownerType);
$lock->set_ownerUri($ownerUri);
$lock->setContent('');

$roolo->addElo($lock);