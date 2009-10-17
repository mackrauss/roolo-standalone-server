<?php
session_start();
require_once dirname(__FILE__).'/../dataModels/Elo.php';
require_once dirname(__FILE__).'/../dataModels/PersonalReflection.php';
require_once dirname(__FILE__).'/../dataModels/Section.php';

require_once dirname(__FILE__).'/../RooloClient.php';

$action = $_REQUEST['action'];

$rooloClient = new RooloClient();

switch($action){

	case 'newReflection':
		echo createPersonalReflection();
	
		break;
	
	case 'loadReflection':
		$reflectionUri = $_REQUEST['reflectionUri'];
		$curReflection = $rooloClient->retrieveElo($reflectionUri);
		
		$query = "type:Section AND owneruri:" . $curReflection->get_uri(true);
		$curReflectionSections = $rooloClient->search($query, 'elo', 'latest');
		$sectionMap = array();
		foreach ($curReflectionSections as $curReflectionSection){
			$sectionMap[$curReflectionSection->get_title()] = $curReflectionSection;
		}
		echo createPersonalReflection($curReflection, $sectionMap);
		break;
		
	case 'saveReflection':
		$reflectionUri = $_REQUST['reflectionUri'];
		
		$personalReflection = new PersonalReflection();
		$personalReflection->set_title($_REQUEST['title']);
		$personalReflection->set_author($_SESSION['username']);
		
		$response = $rooloClient->addElo($personalReflection);
		$savedPersonalReflection = new PersonalReflection($response);

		// adding the sections belonging to this personal reflection
		$sections = $savedPersonalReflection->getSections();
		foreach($sections as $sectionId => $sectionTitle){
			$curSectionContent = $_REQUEST[$sectionId];
			$section = new Section();
			$section->set_uri('');
			$section->set_author($_SESSION['username']);
			$section->set_datecreated('');
			$section->set_datelastmodified('');
			$section->set_title($sectionId);
			$section->set_ownerType('PersonalReflection');
			$section->set_ownerUri($savedPersonalReflection->get_uri());
			$section->setContent($curSectionContent);
			
			$rooloClient->addElo($section);
		}
	
		echo $personalReflection->get_title() . " was successfully saved.";
		break;

}

function createPersonalReflection($reflection=null, $reflectionSectionMap=array()){

	$rooloClient = new RooloClient();
	
	if ($reflection != null){
		$reflectionUri = $reflection->get_uri();
		$reflectionTitle = $reflection->get_title();
	}else{
		$reflectionUri = '';
		$reflectionTitle = '';
	}
	
	$sections = PersonalReflection::getSections();
	
	$output = "<form action='' method='POST'>";
	
	$output .= "<h3>Title</h3> <input type='text' id='reflectionTitle' name='reflectionTitle' value='$reflectionTitle'/>";
	
	foreach($sections as $sectionId => $sectionTitle){
		$sectionContent = '';
		if (isset($reflectionSectionMap[$sectionId])){
			$sectionContent = $reflectionSectionMap[$sectionId]->getContent();
		}
		$output .= "<h3>" . $sectionTitle . "</h3>";
		$output .= "<textarea id='$sectionId' name='$sectionId' cols='60' rows='4'>$sectionContent</textarea><br/>";
	}

	$output .= "<input type='button' class='smallButton' value='save' onClick='saveReflection(\"$reflectionUri\")'/>";
	
	$output .= "</form>";
	
	return $output;
}

?>