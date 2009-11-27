<?php
require_once dirname(__FILE__).'/Elo.php';

class UploadedSolution extends Elo {


	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'UploadedSolution');
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