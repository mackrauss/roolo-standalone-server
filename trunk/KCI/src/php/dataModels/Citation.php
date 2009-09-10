<?php

require_once ('Elo.php');

class Citation extends Elo {

	private $_allMetadata = array();
	
	private $_uri = '';
	private $_title = '';
	private $_author = '';
	private $_type = '';
	private $_dateCreated = '';
	private $_dateModified = '';
	private $_version = '';
	private $_desc = '';
	private $_keywords = array();
	private $_contributors = array();
	
	
	public function __construct($xml=null){
		parent::__construct($xml);
		$this->_allMetadata = parent::getAllMetadata();
		$this->fillMetadata ($this->_allMetadata);
		$this->_allMetadata['type'] = 'Citation';
	}
	
	
	public function fillMetadata($allMetadata){

		$this->_uri = $allMetadata['uri'];
		$this->_title = $allMetadata['title'];
		$this->_author = $allMetadata['author'];
		$this->_dateCreated = $allMetadata['datecreated'];
		$this->_dateModified = $allMetadata['datemodified'];
		$this->_version = $allMetadata['version'];
		$this->_desc = $allMetadata['desc'];
		
		$keywords = $allMetadata['keywords'];
		$this->_keywords = explode(',', $keywords);
		
		$contributors = $allMetadata['contributors'];
		$this->_contributors = explode(',', $contributors);
		
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
	public function get_contributors() {
		return $this->_contributors;
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
	public function get_dateModified() {
		return $this->_dateModified;
	}
	
	/**
	 * @return unknown
	 */
	public function get_desc() {
		return $this->_desc;
	}
	
	/**
	 * @return unknown
	 */
	public function get_keywords() {
		return $this->_keywords;
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
	public function get_uri() {
		return $this->_uri;
	}
	
	/**
	 * @return unknown
	 */
	public function get_version() {
		return $this->_version;
	}
	
	/**
	 * @param unknown_type $_author
	 */
	public function set_author($_author) {
		$this->_author = $_author;
		$this->updateMetadata('author', $_author);
	}
	
	/**
	 * @param unknown_type $_contributors
	 */
	public function set_contributors($_contributors) {
		$this->_contributors = $_contributors;
		$this->updateMetadata('contributors', $_contributors);
	}
	
	/**
	 * @param unknown_type $_dateCreated
	 */
	public function set_dateCreated($_dateCreated) {
		$this->_dateCreated = $_dateCreated;
		$this->updateMetadata('dateCreated', $_dateCreated);
	}
	
	/**
	 * @param unknown_type $_dateModified
	 */
	public function set_dateModified($_dateModified) {
		$this->_dateModified = $_dateModified;
		$this->updateMetadata('dateModified', $_dateModified);		
	}
	
	/**
	 * @param unknown_type $_desc
	 */
	public function set_desc($_desc) {
		$this->_desc = $_desc;
		$this->updateMetadata('description', $_desc);
	}
	
	/**
	 * @param unknown_type $_keywords
	 */
	public function set_keywords($_keywords) {
		$this->_keywords = $_keywords;
		$this->updateMetadata('keywords', $_keywords);
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
	 * @param unknown_type $_version
	 */
	public function set_version($_version) {
		$this->_version = $_version;
		$this->updateMetadata('version', $_version);
	}
	
	/**
	 * @return unknown
	 */
	public function get_type() {
		return $this->_type;
	}
	
}

?>