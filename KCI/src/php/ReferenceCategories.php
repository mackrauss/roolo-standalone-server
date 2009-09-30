<?php

class RefereneceCategories {
	
	private $_referenceCategories;
	
	public function __construct(){
		$this->_referenceCategories = array('cat01', 'cat02', 'cat03', 'cat04');
	}
	
	public function getReferenceCategories(){
		return $this->_referenceCategories;
	}
}

?>