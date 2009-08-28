<?php

require_once ('Elo.php');

class Comment extends Elo {
	
	private $_allMetadata = array();
	
	private $_uri = '';
	private $_title = '';
	private $_author = '';
	private $_commentText = '';
	private $_dateCreated = '';
	
	private $_ownerUri = '';
	private $_ownerType = '';
	
	public function __construct($xml=null){
		parent::__construct($xml);
		$this->_allMetadata = parent::getAllMetadata();
		$this->fillMetadata ($this->_allMetadata);
	}
	
	
	public function fillMetadata($allMetadata){

		$this->_uri = $allMetadata['uri'];
		$this->_title = $allMetadata['title'];
		$this->_author = $allMetadata['author'];
		$this->_commentText = $allMetadata['commentText'];
		$this->_dateCreated = $allMetadata['datecreated'];
		
		$this->_articleUri = $allMetadata['articleuri'];
		$this->_ownerType = $allMetadata['ownertype'];
		
	}
	
	
	/**
	 * @return unknown
	 */
	public function get_articleUri() {
		return $this->_articleUri;
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
	public function get_commentText() {
		return $this->_commentText;
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
	 * @param unknown_type $_articleUri
	 */
	public function set_articleUri($_articleUri) {
		$this->_articleUri = $_articleUri;
	}
	
	/**
	 * @param unknown_type $_author
	 */
	public function set_author($_author) {
		$this->_author = $_author;
	}
	
	/**
	 * @param unknown_type $_commentText
	 */
	public function set_commentText($_commentText) {
		$this->_commentText = $_commentText;
	}
	
	/**
	 * @param unknown_type $_dateCreated
	 */
	public function set_dateCreated($_dateCreated) {
		$this->_dateCreated = $_dateCreated;
	}
	
	/**
	 * @param unknown_type $_ownerType
	 */
	public function set_ownerType($_ownerType) {
		$this->_ownerType = $_ownerType;
	}
	
	/**
	 * @param unknown_type $_title
	 */
	public function set_title($_title) {
		$this->_title = $_title;
	}
	
	/**
	 * @param unknown_type $_uri
	 */
	public function set_uri($_uri) {
		$this->_uri = $_uri;
	}

}

?>