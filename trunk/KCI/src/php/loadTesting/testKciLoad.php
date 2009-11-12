<?php
require_once dirname(__FILE__).'/../RooloClient.php';
require_once dirname(__FILE__).'/../dataModels/Reference.php';
require_once dirname(__FILE__).'/../dataModels/Tag.php';

$rooloClient = new RooloClient();
if (isset($_REQUEST['Author'])){
		$author = $_REQUEST['Author'];
}else{
	$author = "Maurice";
}

$uris = array();

for ($i=1; $i<=30; $i++){
	$submittedReference = new Reference();
	$submittedReference->set_title("title_".$i);
	$submittedReference->set_author($author);
	$submittedReference->set_annotation("annotationText_".$i);
	$submittedReference->set_citation("citationText_".$i);
	
	//echo "reference Category =".$_POST["referenceCategoryList"];
	$submittedReference->set_category("History of Global Change");
	$submittedReference->set_datecreated('');
	$submittedReference->set_uri('');
	$submittedReference->set_version('');

	
	$referenceEloXml = $rooloClient->addElo($submittedReference);
	printPre($referenceEloXml);
	$reference = new Reference($referenceEloXml);
	
	$ownerUri = $reference->get_uri();

	$tagsArray = array(1 => "tag1", 2 => "tag2", 3 => "tag3", 4 => "tag4", 5 => "tag5", 6 => "tag6", 7 => "tag7");
	
	foreach ($tagsArray as $tagTitle){
		$query = "type:Tag AND owneruri:". $reference->get_uri(true) . " AND title:$tagTitle AND status:active";
		$results = $rooloClient->search($query, 'metadata', 'latest');
		if (sizeof($results) == 0){
			$tagObject = new Tag();
			$tagObject->set_ownerType("Reference");
			$tagObject->set_author($author);
			$tagObject->set_ownerUri($ownerUri);
			$tagObject->set_title(trim($tagTitle));
			$tagObject->set_uri('');
			$tagObject->set_version('');
			$tagObject->set_status('active');
			
			$savedEloXml = $rooloClient->addElo($tagObject);
			printPre($savedEloXml);
			
			$tagObject = new Tag($savedEloXml);
			$tagObject->set_title($tagObject->get_title().'_updated');
			$updateResult = $rooloClient->updateElo($tagObject);
			printPre($updateResult);
		}
	}
	
	$reference->set_title($reference->get_title()+"_updated");
	$updateResult = $rooloClient->updateElo($reference);
	printPre($updateResult);
	
	// current time
	echo date('h:i:s') . "\n";

	// sleep for 10 seconds
	sleep(5);

	// wake up !
	echo date('h:i:s') . "\n";
}

function printPre($content){
	echo "<pre>$content</pre><br/><br/>\n";
}
?>