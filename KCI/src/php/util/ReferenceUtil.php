<?php
require_once dirname(__FILE__).'/../dataModels/Reference.php';

function generateAttachedReference($reference){
	$o = '';
	
	$refId = $reference->get_id();
	$refCitation = $reference->get_citation();
	$refUri = $reference->get_uri();
	
	$divId = $refUri.'_link_div';
	
	$o .= "<div id='$divId' name='$divId'>";
	$o .= "<a href='/src/php/referencePage.php?id=$refId' target='_blank'>$refCitation</a>";
	$o .= "<img src='/src/images/cross.png' width='10px' height='10px' onclick=\"return removeReferenceFromArticle('$refUri')\" />";
	$o .= '</div>';
	
	return $o;
}
