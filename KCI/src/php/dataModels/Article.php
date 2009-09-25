<?php

require_once ('Elo.php');

class Article extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Article');
	}

	
	/**
	 * @return unknown
	 */
	public function get_desc() {
		return parent::getMetadata('desc');
	}
	
	
	/**
	 * @param unknown_type $_desc
	 */
	public function set_desc($_desc) {
		parent::addMetadata('description', $_desc);
	}
}

?>