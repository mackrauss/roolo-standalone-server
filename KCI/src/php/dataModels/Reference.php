<?php

require_once ('Elo.php');
require_once 'domLib/simple_html_dom.php';

class Reference extends Elo {
	
	// The constructor will extract the annotation and citation from
	// the content.
	private $_annotation = '';
	private $_citation = '';
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Reference');
		
		$content = parent::getContent();
		$dom = str_get_dom($content);
		
		$annotation = $dom->find('annotation');
		$this->_annotation = $annotation[0]->innertext();
		
		$citation = $dom->find('citation');
		$this->_citation = $citation[0]->innertext();
	}
	
	public function get_citation(){
		return $this->_citation;
	}
	
	
	public function get_annotation(){
		return $this->_annotation;
	}

	public function get_category(){
		return parent::getMetadata('category');
	}
	
	public function set_citation($citation){
		$this->_citation = $citation;
		$this->updateContent();
	}
	
	public function set_annotation ($annotation){
		$this->_annotation = $annotation;
		$this->updateContent();
	}
	
	public function set_category ($category){
		parent::addMetadata('category', $category);
	}
	
	public function updateContent (){
		$content = "<annotation>" . $this->get_annotation() . "</annotation> <citation>" . $this->get_citation() . "</citation>";
		parent::setContent($content);
	}
	
}

?>