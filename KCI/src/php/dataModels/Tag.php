<?php

require_once ('Elo.php');

class Tag extends Elo {
	
private $_allMetadata = array();
	
	private $_uri = '';
	private $_author = '';
	private $_dateCreated = '';
	private $_dateDeleted = '';
	
	private $_ownerUri = '';
	// what is this tag for ('proposal, citation, or article)
	private $_ownerType = '';
	private $_tag = '';
	
	public function __construct($xml=null){
		parent::__construct($xml);
		$this->_allMetadata = parent::getAllMetadata();
		$this->fillMetadata ($this->_allMetadata);
	}
	
	
	public function fillMetadata($allMetadata){

		$this->_uri = $allMetadata['uri'];
		$this->_author = $allMetadata['author'];
		$this->_dateCreated = $allMetadata['datecreated'];
		$this->_dateDeleted = $allMetadata['datedeleted'];
		$this->_ownerUri = $allMetadata['owneruri'];
		$this->_ownerType = $allMetadata['ownertype'];
		$this->_tag = $allMetadata['tag'];
		
	}
}

?>