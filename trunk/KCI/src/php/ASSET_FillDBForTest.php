<?php
require_once 'dataModels\Reference.php';
require_once 'dataModels\Tag.php';
require_once 'RooloClient.php';

$rooloClient = new RooloClient();
$reference = new Reference();
for ($i=1; $i <=4; $i++){
	$reference->set_annotation('annotation of cat01___'.$i);
	$reference->set_author('cat01_author_'.$i);
	$reference->set_category('cat01');
	$reference->set_citation('citation of cat01___'.$i);
	$reference->set_datecreated('');
	$reference->set_datelastmodified('');
	$reference->set_title('cat01_title_'.$i);
	$reference->set_uri('');
	$reference->set_version('');
	echo $rooloClient->addElo($reference);
}
//for ($i=1; $i <=4; $i++){
//	$reference->set_annotation('annotation of cat01___'.$i);
//	$reference->set_author('cat02_author_'.$i);
//	$reference->set_category('cat02');
//	$reference->set_citation('citation of cat01___'.$i);
//	$reference->set_datecreated('');
//	$reference->set_datelastmodified('');
//	$reference->set_title('cat02_title_'.$i);
//	$reference->set_uri('');
//	$reference->set_version('');
//	echo $rooloClient->addElo($reference);
//}
//for ($i=1; $i <=4; $i++){
//	$reference->set_annotation('annotation of cat01___'.$i);
//	$reference->set_author('cat03_author_'.$i);
//	$reference->set_category('cat03');
//	$reference->set_citation('citation of cat01___'.$i);
//	$reference->set_datecreated('');
//	$reference->set_datelastmodified('');
//	$reference->set_title('cat03_title_'.$i);
//	$reference->set_uri('');
//	$reference->set_version('');
//	echo $rooloClient->addElo($reference);
//}
	
$tag = new Tag();
	
for ($i=1; $i <=4; $i++){
	$tag->set_author('tag_author_1');
	$tag->set_datecreated('');
	$tag->set_datedeleted('');
	$tag->set_ownertype('Reference');
	$tag->set_owneruri($i.'.Reference');
	$tag->set_title('tag_title_1');
	$tag->set_uri('');
	$tag->set_version('');
	echo $rooloClient->addElo($tag);
}

?>