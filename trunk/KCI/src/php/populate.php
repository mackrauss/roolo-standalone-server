<?php
require_once('RooloClient.php');
require_once('dataModels/Article.php');
require_once('dataModels/Tag.php');
require_once('dataModels/Section.php');
require_once('dataModels/Reference.php');

//deleteAll();
//$articles = addArticles();
//addSectionsToArticles($articles);
//addTagsToArticles($articles);
//$references = addReferences();

function addReferences(){
	$roolo = new RooloClient();
	$references = array();
	
	for ($i=0;$i<5;$i++){
		$reference = new Reference();
		
		$reference->set_uri('');
		$reference->set_author('author');
		$reference->set_datecreated('');
		$reference->set_datelastmodified('');
		$reference->set_title('my ref'.$i);
		$reference->set_citation('citation'.$i);
		$reference->set_annotation('annotation'.$i);
		$reference->set_category('category'.$i);
		
		$response = $roolo->addElo($reference);
		$references[] = new Reference($response);
	}
	
	return $references;
}

function deleteAll(){
	$roolo = new RooloClient();
	$roolo->deleteAll();
}

function addSectionsToArticles($articles){
	$roolo = new RooloClient();
	$sections = Article::getSections();
	
	foreach($articles as $article){
		$articleUri = $article->get_uri();
		
		foreach($sections as $sectionCode => $sectionTitle){
			$section = new Section();
			$section->set_uri('');
			$section->set_author('author'.$sectionCode);
			$section->set_datecreated('');
			$section->set_datelastmodified('');
			$section->set_title($sectionCode);
			$section->set_ownerType('Article');
			$section->set_ownerUri($articleUri);
			
			$response = $roolo->addElo($section);
//			$sectionElos[$sectionCode] = new Section($response);
		}
	}
	
}

function addTagsToArticles($articles){
	$roolo = new RooloClient();
	foreach($articles as $article){
		for ($i=0;$i<5;$i++){
			$tag = new Tag();
			$tag->set_uri('');
			$tag->set_author('author'.$i);
			$tag->set_datecreated('');
			$tag->set_datelastmodified('');
			$tag->set_title('title'.$i);
			$tag->set_ownertype('Article');
			$tag->set_owneruri($article->get_uri());
			
			$response = $roolo->addElo($tag);
			echo $response;
		}
	}
}

function addArticles(){
	$roolo = new RooloClient();
	$articles = array();
	
	for ($i=0;$i<5;$i++){
		$article = new Article();
		$article->set_uri('');
		$article->set_author('author'.$i);
		$article->set_datecreated('');
		$article->set_datelastmodified('');
		$article->set_title('title'.$i);
		$article->set_desc('article description'.$i);
		
		$response = $roolo->addElo($article);
		
		// response is supposed to be an ELO, so let's create objects out of them
		$articles[] = new Article($response);
	}
	
	return $articles;
}

?>