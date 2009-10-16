<?php
require_once dirname(__FILE__).'/Elo.php';

class Section extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Section');
	}

	public function get_sectionType(){
		return parent::getMetadata('sectiontype');
	}
	 
	public function set_sectionType($sectionType){
		parent::addMetadata('sectiontype', $sectionType);
	}
	
	public function get_ownerType(){
		return parent::getMetadata('ownertype');
	}
	
	public function set_ownerType($ownerType){
		parent::addMetadata('ownertype', $ownerType);
	}
	
	public function get_ownerUri(){
		return parent::getMetadata('owneruri');
	}
	
	public function set_ownerUri($ownerUri){
		parent::addMetadata('owneruri', $ownerUri);
	}
}

?>