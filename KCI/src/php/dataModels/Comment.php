<?php

require_once ('Elo.php');

class Comment extends Elo {
	
	private $_allMetadata = array();
	
	private $_uri = '';
	private $_title = '';
	private $_author = '';
	private $_type = '';
	private $_version = '';


	private $_dateCreated = '';
	
	private $_ownerUri = '';
	private $_ownerType = '';
	
	public function __construct($xml=null){
		parent::__construct($xml);
		$this->_allMetadata = parent::getAllMetadata();
		$this->fillMetadata ($this->_allMetadata);
		$this->_type = 'Comment';
	}
	
	
	public function fillMetadata($allMetadata){

		$this->_uri = $allMetadata['uri'];
		$this->_title = $allMetadata['title'];
		$this->_author = $allMetadata['author'];
		$this->_commentText = $allMetadata['commentText'];
		$this->_dateCreated = $allMetadata['datecreated'];
		
		$this->_ownerUri = $allMetadata['articleuri'];
		$this->_ownerType = $allMetadata['ownertype'];
		
	}
	
	/**
	 * Everytime a metadata is modified, we need to update
	 * our list of metadatas
	 *
	 * @param unknown_type $fieldName
	 * @param unknown_type $fieldValue
	 */
	public function updateMetadata($fieldName, $fieldValue){
		$this->_allMetadata[$fieldName] = $fieldValue;
	}
	
	
	
	/**
	 * @return unknown
	 */
	public function get_author() {
		return $this->_author;
	}
	
	
	/**
	 * @return unknown
	 */
	public function get_dateCreated() {
		return $this->_dateCreated;
	}
	
	/**
	 * @return unknown
	 */
	public function get_ownerType() {
		return $this->_ownerType;
	}
	
	/**
	 * @return unknown
	 */
	public function get_ownerUri() {
		return $this->_ownerUri;
	}
	
	/**
	 * @return unknown
	 */
	public function get_title() {
		return $this->_title;
	}
	
	/**
	 * @return unknown
	 */
	public function get_type() {
		return $this->_type;
	}
	
	/**
	 * @return unknown
	 */
	public function get_uri() {
		return $this->_uri;
	}
	
	/**
	 * @param unknown_type $_author
	 */
	public function set_author($_author) {
		$this->_author = $_author;
		$this->updateMetadata('author', $_author);
	}
	
	
	/**
	 * @param unknown_type $_dateCreated
	 */
	public function set_dateCreated($_dateCreated) {
		$this->_dateCreated = $_dateCreated;
		$this->updateMetadata('dateCreated', $_dateCreated);
	}
	
	/**
	 * @param unknown_type $_ownerType
	 */
	public function set_ownerType($_ownerType) {
		$this->_ownerType = $_ownerType;
		$this->updateMetadata('ownerType', $_ownerType);
	}
	
	/**
	 * @param unknown_type $_ownerUri
	 */
	public function set_ownerUri($_ownerUri) {
		$this->_ownerUri = $_ownerUri;
		$this->updateMetadata('ownerUri', $_ownerUri);
	}
	
	/**
	 * @param unknown_type $_title
	 */
	public function set_title($_title) {
		$this->_title = $_title;
		$this->updateMetadata('title', $_title);
	}
	
	/**
	 * @param unknown_type $_uri
	 */
	public function set_uri($_uri) {
		$this->_uri = $_uri;
		$this->updateMetadata('uri', $_uri);
	}
	
	/**
	 * @return unknown
	 */
	public function get_version() {
		return $this->_version;
	}
	
	/**
	 * @param unknown_type $_version
	 */
	public function set_version($_version) {
		$this->_version = $_version;
	}

}

?>