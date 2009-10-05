<?php
require_once dirname(__FILE__).'/Elo.php';

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
		parent::addMetadata('desc', $_desc);
	}
	
	public static function getSections(){
		return array(
					'ted' => 'Thermal Energy Distribution (Atmosphere & Ocean)',
					'ghe' => 'Green House Effect',
					'css' => 'Carbon Sinks and Sources',
					'evi' => 'Evidence',
					'mas' => 'Models and Scenarios',
					'leg' => 'Legislation',
					'rem' => 'Remediation'
				);
	}
	
	public static function getScienceSections(){
		return array(
					'ted',
					'ghe',
					'css'
				); 
	}
	
	public static function getUncatSections(){
		return array(
					'evi',
					'mas',
					'leg',
					'rem'
				);
	}
}

?>