<?php
require_once dirname(__FILE__).'/Elo.php';

class Question extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Question');
	}

	/**
	 * @return the subtype
	 */
	public function get_subType(){
		return parent::getMetadata('subtype');
	}
	 
	/**
	 * @param $subType this question subtype
	 */
	public function set_subType($subType){
		parent::addMetadata('subtype', $subType);
	}
	 
	/**
	 * @return path+filename of this question
	 */
	public function get_path(){
		return parent::getMetadata('questionpath');
	}
	
	/**
	 * @param $path path+filename of this question
	 */
	public function set_path($path){
		parent::addMetadata('questionpath', $path);
	}
	/**
	 * @return the master soulution of this question
	 */
	public function get_masterSolution(){
		return parent::getMetadata('mastersolution');
	}
	
	/**
	 * @param $masterSolution is the master solution of this question
	 */
	public function set_masterSoulution($masterSolution){
		parent::addMetadata('masterSolution', $masterSolution);
	}
	
}

?>