<?php
session_start();
require_once dirname(__FILE__).'/../dataModels/Elo.php';
require_once dirname(__FILE__).'/../dataModels/PersonalReflection.php';
require_once dirname(__FILE__).'/../dataModels/Section.php';
require_once dirname(__FILE__).'/../dataModels/Poll.php';

require_once dirname(__FILE__).'/../RooloClient.php';

$action = $_REQUEST['action'];

$rooloClient = new RooloClient();

switch($action){

	case 'newReflection':
		echo createPersonalReflection();
	
		break;
	
	case 'loadReflection':
		/*
		 * Retrieve PERSONAL REFLECTION
		 */
		$reflectionUri = $_REQUEST['reflectionUri'];
		$curReflection = $rooloClient->retrieveElo($reflectionUri);
		
		/*
		 * Retrieve SECTION
		 */
		$query = "type:Section AND owneruri:" . $curReflection->get_uri(true);
		$curReflectionSections = $rooloClient->search($query, 'metadata', 'latest');
		$sectionMap = array();
		foreach ($curReflectionSections as $curReflectionSection){
			$sectionMap[$curReflectionSection->get_title()] = $curReflectionSection;
		}
		
		/*
		 * Retrieve CHOICES
		 */
		$query = "type:Poll AND owneruri:" . $curReflection->get_uri(true);
		$curReflectionChoices = $rooloClient->search($query, 'metadata', 'latest');
		$choiceMap = array();
		foreach ($curReflectionChoices as $curReflectionChoice){
			$choiceMap[$curReflectionChoice->get_title()] = $curReflectionChoice;
		}
		
//		echo "<pre>";
//		echo $query;
//		print_r($curReflectionChoices);
//		echo "</pre>";
		echo createPersonalReflection($curReflection, $sectionMap, $choiceMap);
		break;
		
	case 'saveReflection':
		$reflectionUri = $_REQUEST['reflectionUri'];
		
		$personalContribChoice = 	$_REQUEST['personalContribChoice']; 
		$personalContribTextarea = 	$_REQUEST['personalContribTextarea'];
		$groupContribChoice = 		$_REQUEST['groupContribChoice'];
		$groupContribTextarea = 	$_REQUEST['groupContribTextarea'];
		$roleChoice = 				$_REQUEST['roleChoice'];
		$roleTextarea = 			$_REQUEST['roleTextarea'];
		
		$choices = array(	'personalContrib' => $personalContribChoice, 
							'groupContrib' => $groupContribChoice, 
							'role' => $roleChoice);
		$sections = array(	'personalContrib' => $personalContribTextarea, 
							'groupContrib' => $groupContribTextarea, 
							'role' => $roleTextarea);
		
		/*
		 * Save the Reflection Object itself
		 */
		$personalReflection = new PersonalReflection();
		$personalReflection->set_title('');
		$personalReflection->set_author($_SESSION['username']);
		$response = $rooloClient->addElo($personalReflection);
		$savedPersonalReflection = new PersonalReflection($response);
		
		/*
		 * Save all Poll (Choice) objects
		 */
		foreach($choices as $choiceId => $choice){
			$curPoll = new Poll();
			$curPoll->set_uri('');
			$curPoll->set_author($_SESSION['username']);
			$curPoll->set_datecreated('');
			$curPoll->set_datelastmodified('');
			$curPoll->set_title($choiceId);
			$curPoll->set_choice($choice);
			$curPoll->set_ownerType('PersonalReflection');
			$curPoll->set_ownerUri($savedPersonalReflection->get_uri());
			$curPoll->setContent('');
			
			$rooloClient->addElo($curPoll);
		}
		
		/*
		 * Save all Section (Textarea) objects
		 */
		foreach($sections as $sectionId => $section){
			$curSection = new Section();
			$curSection->set_uri('');
			$curSection->set_author($_SESSION['username']);
			$curSection->set_datecreated('');
			$curSection->set_datelastmodified('');
			$curSection->set_title($sectionId);
			$curSection->set_ownerType('PersonalReflection');
			$curSection->set_ownerUri($savedPersonalReflection->get_uri());
			$curSection->setContent($section);
			
			$rooloClient->addElo($curSection);
		}
		
		
//		echo $personalContribChoice . " - " . $personalCotribTextarea . " - " . $groupContribChoice . " - " . $groupContribTextarea . " - " . $roleChoice . " - " . $roleTextarea;
// 		adding the sections belonging to this personal reflection
//		$sections = $savedPersonalReflection->getSections();
//		foreach($sections as $sectionId => $sectionTitle){
//			$curSectionContent = $_REQUEST[$sectionId];
//			$section = new Section();
//			$section->set_uri('');
//			$section->set_author($_SESSION['username']);
//			$section->set_datecreated('');
//			$section->set_datelastmodified('');
//			$section->set_title($sectionId);
//			$section->set_ownerType('PersonalReflection');
//			$section->set_ownerUri($savedPersonalReflection->get_uri());
//			$section->setContent($curSectionContent);
//			
//			$rooloClient->addElo($section);
//		}
//	
        echo "<a href='#' onClick='return loadReflection(\"" . $savedPersonalReflection->get_uri(). "\");'>" . @date('F jS',$savedPersonalReflection->get_datelastmodified()/1000) . "</a><br/>";
		break;
}

function createPersonalReflection($reflection=null, $reflectionSectionMap=null, $reflectionChoiceMap=null){

	$rooloClient = new RooloClient();
	
	if ($reflection != null){
		$reflectionUri = $reflection->get_uri();
		$reflectionTitle = $reflection->get_title();
	}else{
		$reflectionUri = '';
		$reflectionTitle = '';
	}
	
	if ($reflectionSectionMap !== null && $reflectionChoiceMap !== null){
		$editMode = false;
	}else{
		$editMode = true;
	}
	
	if ($reflectionSectionMap !== null){
		$personalContribSectionContent = $reflectionSectionMap['personalContrib']->getContent();
		$groupContribSectionContent    = $reflectionSectionMap['groupContrib']->getContent();
		$roleSectionContent            = $reflectionSectionMap['role']->getContent();
	}else{
		$personalContribSectionContent = '';
		$groupContribSectionContent    = '';
		$roleSectionContent            = '';
	}
	
	if ($reflectionChoiceMap !== null){
		$personalContribChoice = $reflectionChoiceMap['personalContrib']->get_choice();
		$groupContribChoice    = $reflectionChoiceMap['groupContrib']->get_choice();
		$roleChoice     = $reflectionChoiceMap['role']->get_choice();
	}else{
		$personalContribChoice = '';
		$groupContribChoice    = '';
		$roleChoice     = '';
	}
	
	$output = '';
	$output .= "<div id='curReflection'>";
	$output .= "<form action='' method='POST'>";
	
	$output .= "<h3>Personal Contribution</h3>";
	$output .= "<h4>Nature of Personal Contribution</h4>";
	$output .= "<input type='radio' name='personalContribChoice' class='personalContribChoice' value='1' ".($personalContribChoice == '1' ? 'checked': '')."/> was I adding information to the page? <br/>";
	$output .= "<input type='radio' name='personalContribChoice' class='personalContribChoice' value='2' ".($personalContribChoice == '2' ? 'checked': '')."/> was I analyzing information on the page and following throught? <br/>";
	$output .= "<input type='radio' name='personalContribChoice' class='personalContribChoice' value='3' ".($personalContribChoice == '3' ? 'checked': '')."/> was I summarizing <br/>";
	$output .= "<br/>";
	$output .= "Include an example of your contribution that highlights your choice<br/>";
	$output .= "<textarea id='personalContribTextarea' name='personalContribTextarea' cols='60' rows='4'>".$personalContribSectionContent."</textarea>";
	$output .= "<br/>";
	
	$output .= "<h3>Group Contribution</h3>";
	$output .= "<h4>Nature of Contribution to the Group</h4>";
	$output .= "<input type='radio' name='groupContribChoice' class='groupContribChoice' value='1' ".($groupContribChoice == '1' ? 'checked': '')."/> Addition <br/>";
	$output .= "<input type='radio' name='groupContribChoice' class='groupContribChoice' value='2' ".($groupContribChoice == '2' ? 'checked': '')."/> Self-Edit <br/>";
	$output .= "<input type='radio' name='groupContribChoice' class='groupContribChoice' value='3' ".($groupContribChoice == '3' ? 'checked': '')."/> Editing Others' Work <br/>";
	$output .= "<br/>";
	$output .= "Include an example of your contribution that highlights your choice<br/>";
	$output .= "<textarea id='groupContribTextarea' name='groupContribTextarea' cols='60' rows='4'>".$groupContribSectionContent."</textarea>";
	$output .= "<br/>";

	$output .= "<h3>Role in Group</h3>";
	$output .= "<h4>What role did you take on in the group?</h4>";
	$output .= "<input type='radio' name='roleChoice' class='roleChoice' value='1' ".($roleChoice == '1' ? 'checked': '')."/> Leader <br/>";
	$output .= "<input type='radio' name='roleChoice' class='roleChoice' value='2' ".($roleChoice == '2' ? 'checked': '')."/> Follower <br/>";
	$output .= "<br/>";
	$output .= "Include an example of your contribution that highlights your choice<br/>";
	$output .= "<textarea id='roleTextarea' name='roleTextarea' cols='60' rows='4'>".$roleSectionContent."</textarea>";
	$output .= "<br/>";
	
	if ($editMode){
		$output .= "<input name='saveReflectionButton' id='saveReflectionButton'' type='button' class='smallButton' value='save' onClick='loadPopup(); saveReflection(\"$reflectionUri\")'/>";
		$output .= "<input name='cancelReflectionButton' id='cancelReflectionButton' type='button' class='smallButton' value='cancel' onClick='cancelNewReflection()'/>";
	}
	$output .= "</form>";
	$output .= "</div>";
	
	return $output;
}

?>