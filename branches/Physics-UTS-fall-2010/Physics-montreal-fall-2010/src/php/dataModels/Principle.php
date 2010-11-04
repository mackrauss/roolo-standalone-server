<?php
require_once dirname(__FILE__).'/Elo.php';

class Principle extends Elo {
	
	private $_pathTypesArray = array("urlPath", "uriPath", "diskPath");
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Principle');
	}

	/*
	 *	The GENERAL variable $name for getter & seter all DataModels
	 *
	 *  $name="uri"
	 *	$name="type" 
	 *	$name="datecreated" 
	 *	$name="datelastmodified" 
	 *	$name="version" 
	 *	$name="title" 
	 *	$name="author" 
	 *	$name="category"  category of the object in physic (e.g "kinematics", "work", "energy" ...
	 * 	$name="path"      Path to stored place of the object. It can be hard disk, in repository or internet 
	 *	$name="pathtype"  type of path determine the stored place type of the object ( e.g. hard disk, internet, ...) 
	 *
	 * 	The SPECIFIC variable $name for getter & seter this DataModel
	 *
	 *   
	 */	
				
	
	public function __set($name, $value) {
		switch($name) {
			case 'pathtype': 
				$value = $this->_pathTypesArray[$value];
        		parent::addMetadata($name, $value);
          		break;
          	case 'content': 
        		parent::setContent($value);
        		break;
        	default:
        		parent::addMetadata($name, $value);
        }
	}

    public function __get($name) {
    	switch ($name){
    		case 'content':
        		$value = parent::getContent();
        		break;
        	default:
			   	$value = parent::getMetadata($name);
    	}
		return $value;
    }
}

?>