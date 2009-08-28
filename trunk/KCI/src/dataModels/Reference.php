<?php

require_once ('Elo.php');

class Reference extends Elo {
	
	private $_allMetadata = array();
	
	private $_uri = '';
	private $_author = '';
	private $_dateCreated = '';
	private $_dateDeleted = '';
	private $_uri1 = '';
	private $_uri2 = '';
	private $_uriType1 = '';
	private $_uriType2 = '';
	
	public function __construct($xml=null){
		parent::__construct($xml);
		$this->_allMetadata = parent::getAllMetadata();
		$this->fillMetadata ($this->_allMetadata);
	}
	
	
	public function fillMetadata($allMetadata){

		$this->_uri = $allMetadata['uri'];
		$this->_title = $allMetadata['title'];
		$this->_author = $allMetadata['author'];
		$this->_dateCreated = $allMetadata['datecreated'];
		$this->_dateDeleted = $allMetadata['datedeleted'];
		$this->_uri1 = $allMetadata['uri1'];
		$this->_uri2 = $allMetadata['uri2'];
		$this->_uriType1 = $allMetadata['uritype1'];
		$this->_uriType2 = $allMetadata['uritype2'];
		
	}
}

?>