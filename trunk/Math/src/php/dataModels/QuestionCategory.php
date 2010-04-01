<?php
require_once dirname(__FILE__).'/Elo.php';

class QuestionCategory extends Elo {
	
	public static $categories = array(
		"Geometery",
		"Exponential",
		"Algebra",
		"Trigonometry"
	);
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'QuestionCategory');
		parent::addMetadata('ismastersolution', false);
	}
	
	
	public function is_masterSolution(){
		return parent::getMetadata('ismastersolution');
	}
	
	public function set_asMasterSolution(){
		parent::addMetadata('ismastersolution', true);
	}
	
	public function get_questionPath(){
		return parent::getMetadata('questionpath');
	}
	
	public function set_questionPath($path){
		parent::addMetadata('questionpath', $path);
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

    public function get_answer() {
      return $this->get_title();
    }
	
}

?>