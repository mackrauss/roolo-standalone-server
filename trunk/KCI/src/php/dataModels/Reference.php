<?php

require_once ('Elo.php');

class Reference extends Elo {
	
	private $_allMetadata = array();
	
	private $_uri = '';
	private $_title = '';
	private $_author = '';
	private $_type = '';
	private $_version = '';
	

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
		$this->_type = 'reference';
	}
	
	
	public function fillMetadata($allMetadata){

		$this->_uri = $allMetadata['uri'];
		$this->_title = $allMetadata['title'];
		$this->_author = $allMetadata['author'];
		$this->_type = $allMetadata['type'];
		$this->_version = $allMetadata['version'];
		$this->_dateCreated = $allMetadata['datecreated'];
		$this->_dateDeleted = $allMetadata['datedeleted'];
		$this->_uri1 = $allMetadata['uri1'];
		$this->_uri2 = $allMetadata['uri2'];
		$this->_uriType1 = $allMetadata['uritype1'];
		$this->_uriType2 = $allMetadata['uritype2'];
		
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
	public function get_uri() {
		return $this->_uri;
	}
	
	/**
	 * @return unknown
	 */
	public function get_uri1() {
		return $this->_uri1;
	}
	
	/**
	 * @return unknown
	 */
	public function get_uri2() {
		return $this->_uri2;
	}
	
	/**
	 * @return unknown
	 */
	public function get_uriType1() {
		return $this->_uriType1;
	}
	
	/**
	 * @return unknown
	 */
	public function get_uriType2() {
		return $this->_uriType2;
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
		$this->updateMetadata('datecreated', $_dateCreated);
	}
	
	/**
	 * @param unknown_type $_dateDeleted
	 */
	public function set_dateDeleted($_dateDeleted) {
		$this->_dateDeleted = $_dateDeleted;
		$this->updateMetadata('datedeleted', $_dateDeleted);
	}
	
	/**
	 * @param unknown_type $_uri
	 */
	public function set_uri($_uri) {
		$this->_uri = $_uri;
		$this->updateMetadata('uri', $_uri);
	}
	
	/**
	 * @param unknown_type $_uri1
	 */
	public function set_uri1($_uri1) {
		$this->_uri1 = $_uri1;
		$this->updateMetadata('uri1', $_uri1);
	}
	
	/**
	 * @param unknown_type $_uri2
	 */
	public function set_uri2($_uri2) {
		$this->_uri2 = $_uri2;
		$this->updateMetadata('uri2', $_uri2);
	}
	
	/**
	 * @param unknown_type $_uriType1
	 */
	public function set_uriType1($_uriType1) {
		$this->_uriType1 = $_uriType1;
		$this->updateMetadata('uritype1', $_uriType1);
	}
	
	/**
	 * @param unknown_type $_uriType2
	 */
	public function set_uriType2($_uriType2) {
		$this->_uriType2 = $_uriType2;
		$this->updateMetadata('uritype2', $_uriType2);
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