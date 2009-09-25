<?php

require_once ('Elo.php');

class Tag extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Tag');
	}
	
	/**
	 * @return unknown
	 */
	public function get_dateDeleted() {
		return parent::getMetadata('datedeleted');
	}
	
	/**
	 * @return unknown
	 */
	public function get_ownerType() {
		return parent::getMetadata('ownertype');
	}
	
	/**
	 * @return unknown
	 */
	public function get_ownerUri() {
		return parent::getMetadata('owneruri');
	}
	
	
	/**
	 * @param unknown_type $_dateDeleted
	 */
	public function set_dateDeleted($_dateDeleted) {
		parent::addMetadata('datedeleted', $_dateDeleted);
	}
	
	/**
	 * @param unknown_type $_ownerType
	 */
	public function set_ownerType($_ownerType) {
		parent::addMetadata('ownertype', $_ownerType);
	}
	
	/**
	 * @param unknown_type $_ownerUri
	 */
	public function set_ownerUri($_ownerUri) {
		parent::addMetadata('owneruri', $_ownerUri);
	}
	
}

?>