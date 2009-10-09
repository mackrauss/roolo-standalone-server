<?php
require_once dirname(__FILE__).'/../dataModels/Tag.php';
 
function generateTags($tagElos, $ownerType, $ownerUri, $prefix){
	$o = '';
	$formId = $prefix."_tags_form";
	$textboxId = $prefix."_tags_textbox";
	$existingTagsDivId = $prefix."_existing_tags";
	
	$o .= "<form name='$formId' id='$formId'>";
	$o .= "    Add tags: <input type='text' name='$textboxId' id='$textboxId' style='margin-bottom: 10px;'/>";
	$o .= "    <input type='button' class='SmallButton' value='Add' onclick=\"addSectionTag('$prefix', '$ownerUri');\" />";
	$o .= "    <div name='$existingTagsDivId' id='$existingTagsDivId' style='float:left; width:100%; margin-bottom: 30px;'>";
	
	foreach($tagElos as $tagElo){
		$curTagUri = $tagElo->get_uri();
		
		$o .= Tag::generateHtml($curTagUri, $tagElo->get_title());
	}
	
	$o .= "    </div>";
	$o .= "</form>";
	
	return $o;
}