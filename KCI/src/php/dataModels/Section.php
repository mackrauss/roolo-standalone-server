<?php

require_once ('Elo.php');

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
}

?>