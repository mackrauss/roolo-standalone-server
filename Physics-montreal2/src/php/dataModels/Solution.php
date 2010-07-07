<?php
require_once dirname(__FILE__).'/Elo.php';

class Solution extends Elo {
	
	private $_pathTypesArray = array("urlPath", "uriPath", "diskPath");
	private $_solutionTypesArray =  array("multipleChoice", "written", "uploaded");

	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Solution');
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
	 *	$name="pathtype"  type of path determine the stored place type of the object 
	 * 					  ( e.g. "UrlPath", "UriPath", "DiskPath")
	 * 					  inested of assigning one of these strings as value for "pathtype"
 	 * 					  user has to use index of "$_pathTypesArray" as an integer
 	 *                    for example 0 for "urlPath", 
 	 *                    			  1 for "uriPath" and
 	 * 								  2 for "diskPath"
	 *
	 * 	The SPECIFIC variable $name for getter & seter this DataModel
	 *
	 * 	$name="owneruri"            Problem URI related to this Object
	 * 	$name="solutionpath"        path to the master solution of the problem object
	 *	$name="solutionpathtype"    type of solution
	 * 					  			(e.g. "Multiplechoice", "Written", "Uploaded")
	 * 					  			inested of assigning one of these strings as value for "solutionpath"
 	 * 					  			user has to use index of elements in "$_solutionTypesArray" as an integer
 	 *                    			for example 0 for "multipleChoice", 
 	 *                    			 			1 for "written" and
 	 * 								 			2 for "uploaded"
	 * 	$name="selectedchoice"      in Multiplechoce problems keeps the selected choice (e.g. "A", "B", "c", ...)
	 * 	$name="content"             get/set the "rationale" from/in content
	 * 	 	    
	 */	
				
	
	public function __set($name, $value) {
		switch($name) {
			case 'pathtype': 
				$value = $this->_pathTypesArray[$value];
        		parent::addMetadata($name, $value);
          		break;
          	case 'solutiontype':
          		$value = $this->_solutionTypesArray[$value];
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
    
    /**
     * adding a new string to content should make a tag of that string. 
     * making a tag should add "<tag name>" as prifix and "</tag name>" as sufix of the string
     * in this method 
     *                 $text is new string for adding to content and
     *                 $tag is just the tag name without "<" , "</" and ">" characters  
	 *
	 * @param $text a string as Element Text
	 * @param $tag  a string as tag withoot "<" and ">"
	 */
    public function buildContentElement($text, $tag){
    	$element  = "<" . $tag . ">";
    	$element .= $text;
    	$element .= "</" .$tag . ">";
    	return $element;
    }
    
    /**
	 * Given the content and tag, remove
	 * the text of specific tag in the content section
	 *
	 * @param $content a string as all content 
	 * @param $tag  a string as tag withoot "<" and ">"
	 */
    public function removeSubElementFromContent($content, $tag){
    	$start = strpos($content, "<".$tag.">" ) - strlen($tag) + 2;
    	$end = strrpos($content,"</".$tag.">");
    	$len = $end - $start;
		return substr($content, $start, $len );
    }
}

?>
