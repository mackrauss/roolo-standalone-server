<?php

require_once ('Elo.php');

class Tag extends Elo {
	
private $_allMetadata = array();
	
	private $_uri = '';
	private $_title = '';
	private $_author = '';
	private $_type = '';
	private $_version = '';


	private $_dateCreated = '';
	private $_dateDeleted = '';
	
	private $_ownerUri = '';
	// what is this tag for ('proposal, citation, or article)
	private $_ownerType = '';
	
	public function __construct($xml=null){
		parent::__construct($xml);
		$this->_allMetadata = parent::getAllMetadata();
		$this->fillMetadata ($this->_allMetadata);
		$this->_allMetadata['type'] = 'Tag';
	}
	
	
	public function fillMetadata($allMetadata){

		$this->_uri = $allMetadata['uri'];
		$this->_title = $allMetadata['title'];
		$this->_author = $allMetadata['author'];
		$this->_dateCreated = $allMetadata['datecreated'];
		$this->_dateDeleted = $allMetadata['datedeleted'];
		$this->_ownerUri = $allMetadata['owneruri'];
		$this->_ownerType = $allMetadata['ownertype'];
		$this->_tag = $allMetadata['tag'];
		
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
	 * We add the metadata to parent's metadata beacause
	 * generateXml() is using parent's metadata
	 *
	 * @return unknown
	 */
	public function generateXml(){
		foreach ($this->_allMetadata as $key => $value){
			parent::addMetadata($key, $value);
		}
		
		return parent::generateXml();
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
	public function get_dateDeleted() {
		return $this->_dateDeleted;
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
	 * @param unknown_type $_dateDeleted
	 */
	public function set_dateDeleted($_dateDeleted) {
		$this->_dateDeleted = $_dateDeleted;
		$this->updateMetadata('dateDeleted', $_dateDeleted);
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
		$this->updateMetadata('belongsTo', $_ownerUri);
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
	public function get_title() {
		return $this->_title;
		
	}
	
	
	/**
	 * @param unknown_type $_title
	 */
	public function set_title($_title) {
		$this->_title = $_title;
		$this->updateMetadata('title', $_title);
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
	public function get_version() {
		return $this->_version;
	}
	
	/**
	 * @param unknown_type $_version
	 */
	public function set_version($_version) {
		$this->_version = $_version;
		$this->updateMetadata('version', $_version);
	}
	
}

?>