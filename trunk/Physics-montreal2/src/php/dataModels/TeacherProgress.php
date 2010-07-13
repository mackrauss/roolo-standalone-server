<?php
require_once dirname(__FILE__).'/Elo.php';

class TeacherProgress extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'TeacherProgress');
	}
	
	public function get_progress(){
		return parent::getMetadata('progress');
	}
	
	public function set_progress($progress){
		parent::addMetadata('progress', $progress);
	}
}

?>