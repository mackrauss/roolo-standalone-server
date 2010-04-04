<?php
require_once dirname(__FILE__).'/Elo.php';

class VoteOnTag extends Elo {


	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'VoteOnTag');
	}

	/**
	 * @return the 
	 */
	public function get_ownerType() {
		return parent::getMetadata('ownertype');
	}

	/**
	 * @param $ownerType
	 */
	public function set_ownerType($ownerType) {
		parent::addMetadata('ownertype', $ownerType);
	}
	
	/**
	 * @return the question URI of this solution
	 */
	public function get_ownerUri() {
		return parent::getMetadata('owneruri');
	}

	/**
	 * @param $_ownerUri the question uri of this solution
	 */
	public function set_ownerUri($_ownerUri) {
		parent::addMetadata('owneruri', $_ownerUri);
	}
	
	/**
	 * @return the saved solution path + filename
	 * the filname is an integer according the saved time of solution file with itd extention
	 */
	public function get_path(){
		return parent::getMetadata('path');
	}

	/**
	 * @param $path is path+filename of uploaded solution file  
	 */
	public function set_path($path){
		parent::addMetadata('path', $path);
	}
	
	
	public function get_answer(){
		return parent::getMetadata('answer');
	}

	public function set_answer($answer){
		parent::addMetadata('answer', $answer);
	}
	
	public function get_tag(){
		return parent::getMetadata('tag');
	}

	public function set_tag($tag){
		parent::addMetadata('tag', $tag);
	}
	
}

?>