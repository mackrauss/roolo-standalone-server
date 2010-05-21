<?php
require_once dirname(__FILE__).'/Elo.php';

class UploadedSolution extends Elo {


	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'UploadedSolution');
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
		return parent::getMetadata('pathOnDisk');
	}

	/**
	 * @param $path is path+filename of uploaded solution file  
	 */
	public function set_path($path){
		parent::addMetadata('pathOnDisk', $path);
	}
	
}

?>