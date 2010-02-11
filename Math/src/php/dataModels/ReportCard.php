<?php
require_once dirname(__FILE__).'/Elo.php';

class ReportCard extends Elo {
	
	public function __construct($xml = null){
		parent::__construct($xml);
		parent::addMetadata('type', 'ReportCard');
	}
	

    public function __get($key) {
      return parent::getMetadata($key);
    }

    public function __set($key, $value) {
      parent::addMetadata($key, $value);
    }
	
}

?>