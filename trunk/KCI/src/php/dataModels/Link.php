<?php

require_once dirname(__FILE__).'/Elo.php';

class Link extends Elo {
	
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Link');
		
	} 
	
	public function get_dateDeleted (){
		return parent::getMetadata('datedeleted');
	}
	
	public function get_uri1 (){
		return parent::getMetadata('uri1');
	}
	
	public function get_uri2 (){
		return parent::getMetadata('uri2');
	}
	
	public function get_type1 (){
		return parent::getMetadata('type1');
	}
	
	public function get_type2 (){
		return parent::getMetadata('type2');
	}
	
	
	public function set_dateDeleted($datedeleted){
		parent::addMetadata('datedeleted', $datedeleted);
	}
	
	public function set_uri1($newUri){
		parent::addMetadata('uri1', $newUri);
	}
	
	public function set_uri2($newUri){
		parent::addMetadata('uri2', $newUri);
	}
	
	public function set_type1($newType){
		parent::addMetadata('type1', $newType);
	}
	
	public function set_type2($newType){
		parent::addMetadata('type2', $newType);
	}
	
}

?>