<?php
/*
 * This script is utitility to create all principles
 * to destinationDir Directory
 * this script makes an Elo Principle Object for each of principles in Repository
 */


require_once '../RooloClient.php';
require_once '../dataModels/Principle.php';

$title = "First";
$content = "An object at rest tends to stay at rest and an object in motion tends to stay in motion with the same speed and in the same direction unless acted upon by an unbalanced force.";
addPrinciple($title, $content);

$title = "Second";
$content = "The acceleration of an object as produced by a net force is directly proportional to the magnitude of the net force, in the same direction as the net force, and inversely proportional to the mass of the object.";
addPrinciple($title, $content);


$title = "Third";
$content = "For every action, there is an equal and opposite reaction.";
addPrinciple($title, $content);
echo "3 principle ElO has created!";


function addPrinciple($title, $content){
	$rooloClient = new RooloClient();
	$principle = new Principle();
	$principle->title = $title;
	$principle->content = $content;
//	echo $principle->generateXml();
	echo $rooloClient->addElo($principle);
}

?>