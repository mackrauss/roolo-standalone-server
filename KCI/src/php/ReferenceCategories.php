<?php

class ReferenceCategories {
	
	private $_referenceCategories;
	
	public function __construct(){
		$this->_referenceCategories = array('cat01', 'cat02', 'cat03', 'cat04', 'cat05', 'cat06', 'cat07', 'cat08', 'cat09', 'cat10',
											'cat11', 'cat12', 'cat13', 'cat14', 'cat15', 'cat16', 'cat17', 'cat18', 'cat19', 'cat20');
	}
	
	public function getReferenceCategories(){
		return $this->_referenceCategories;
	}
}

?>