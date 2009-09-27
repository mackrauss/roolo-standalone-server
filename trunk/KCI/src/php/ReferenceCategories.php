<?php

class RefereneceCategories {
	
	private $_referenceCategories;
	
	public function __construct(){
		$this->_referenceCategories = array('cat1', 'cat2', 'cat3', 'cat4');
	}
	
	public function getReferenceCategories(){
		return $this->_referenceCategories;
	}
}

?>