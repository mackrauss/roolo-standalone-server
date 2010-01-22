<?php
require_once './php/RooloClient.php';
require_once './php/dataModels/Reference.php';
require_once './php/dataModels/Tag.php';


/**
 * TODO Perform the following run and measure the corresponding:
 * 
 * Scenario:
 * 	- 1 user
 *  - 100 operations
 * 
 * Then measure the cost per operation.
 * 
 * Repeat the above process and see if we see a significant difference
 * in each iteration of the process. 
 * 
*/





$rooloClient = new RooloClient();
if (isset($_REQUEST['Author'])){
		$author = $_REQUEST['Author'];
}else{
	$author = "rokham";
}
for ($i=1; $i<=50; $i++){
	$submittedReference = new Reference();
	$submittedReference->set_title("new_title_".$i);
	$submittedReference->set_author($author);
	$submittedReference->set_annotation("annotationText_".$i);
	$submittedReference->set_citation("citationText_".$i);
	
	//echo "reference Category =".$_POST["referenceCategoryList"];
	$submittedReference->set_category("History of Global Change");
	$submittedReference->set_datecreated('');
	$submittedReference->set_uri('');
	$submittedReference->set_version('');

	$reference = new Reference($rooloClient->addElo($submittedReference));
	$ownerUri = $reference->get_uri();

	$tagsArray = array(1 => "tag1", 2 => "tag2", 3 => "tag3", 4 => "tag4", 5 => "tag5",
					   6 => "tag6",	7 => "tag7", 8 => "tag8", 9 => "tag9", 10 => "tag10");
	
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
			$rooloClient->addElo($tagObject); 
		}
	}
	// current time
	echo date('h:i:s') . "\n";

	// sleep for 10 seconds
	sleep(0);

	// wake up !
	echo date('h:i:s') . "\n";
}		
?>