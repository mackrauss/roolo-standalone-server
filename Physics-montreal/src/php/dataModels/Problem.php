<?php
require_once dirname(__FILE__).'/Elo.php';

class Problem extends Elo {
	
	private $_pathTypesArray = array("urlPath", "uriPath", "diskPath");
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Problem');
	}

//	public function selectPathType ($index){
//		$pathTypesArray = array("URLPath", "URIPath", "DISKPath");
//		if ($index < 3){
//			return $pathTypesArray[$index];
//		}else{ 
//			return "";
//		}	
//	}
	
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
	 *	$name="pathtype"  type of path determine the stored place type of the object 
	 * 					  ( e.g. "UrlPath", "UriPath", "DiskPath")
	 * 					  inested of assign one of these strings as value for "pathtype"
 	 * 					  user has to use index of "$_pathTypesArray" as an integer
 	 *                    for example 0 for "urlPath", 
 	 *                    			  1 for "uriPath" and
 	 * 								  2 for "diskPath"
	 * 
	 * 	The SPECIFIC variable $name for getter & seter this DataModel
	 *
	 * 	$name="solutionpath"        path to stored place of the master solution of the problem object
	 * 	$name="solutionpathtype"    type of path determine the stored place type of the master solution
	 * 	$name="principleuri"        related principle to this problem object
	 * 	$name="mcmastersolution"    master solution for multiple choice problem
	 *    
	 */	
				
	
	public function __set($name, $value) {
		
		switch($name) {
			case 'pathtype': 
				$value = $this->_pathTypesArray[$value];
          		break;
		}
    	parent::addMetadata($name, $value);
	}
		
    public function __get($name) {
		return parent::getMetadata($name);
    }
    
    public function set_principleUri($uri){
    	parent::addMetadata('principleUri', $uri);
    }
    
    public  function get_principleUri(){
    	return parent::getMetadata('principleUri');
    }
    
    public function set_principleName($name){
    	parent::addMetadata('principleName', $name);
    }
    
    public function get_principleName(){
    	return parent::getMetadata('principleName');
    }
}















?>