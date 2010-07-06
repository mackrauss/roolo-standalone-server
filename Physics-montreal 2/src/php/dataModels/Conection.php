<?php
require_once dirname(__FILE__).'/Elo.php';

class Conection extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Conection');
	}
	
	public function selectPathType ($index){
		$pathTypesArray = array("URLPath", "URIPath", "DISKPath");
		if ($index < 3){
			return $pathTypesArray[$index];
		}else{ 
			return "";
		}	
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
	 *	$name="category"  category of the object in physic (e.g "kinematics", "work", "energy" ...)
	 * 	$name="path"      Path to stored place of the object. It can be hard disk, in repository or internet 
	 *	$name="pathtype"  type of path determine the stored place type of the object ( e.g. hard disk, internet, ...) 
	 *
	 * 	The SPECIFIC variable $name for getter & seter this DataModel
	 *
	 * 	$name="sourceuri"     source object URI
	 * 	$name="sourcetype"    source object type (e.g. Problem, Equation, Media, ...)
	 * 	$name="desturi"       destination object URI
	 * 	$name="desttype"      destination object type (e.g. Problem, Equation, Media, ...)
	 *  $name="positivevote"     
	 *  $name="negativevote"     
	 * 
	 *  the reason and basis that student has given this vote is stored in contents  
	 */ 	 	
				
	
	public function __set($name, $value) {
		parent::addMetadata($name, $value);
	}

    public function __get($name) {
		return parent::getMetadata($name);
    }
}

?>