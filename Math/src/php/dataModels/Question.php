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
		return parent::getMetadata('masterSolution');
	}
	
	/**
	 * @param $masterSolution is the master solution of this question
	 */
	public function set_masterSoulution($masterSolution){
		parent::addMetadata('masterSolution', $masterSolution);
	}
	
	public function get_tags(){
		return parent::getMetadata('tags');
	}
	
	public function set_tags($tags){
		parent::addMetadata('tags', $tags);
	}
	
	public function get_numTags(){
		return parent::getMetadata('numtags');
	}
	
	public function set_numTags($numTags){
		parent::addMetadata('numtags', $numTags);
	}
	
}

?>