<?php

require_once dirname(__FILE__).'/../domLib/simple_html_dom.php';
require_once dirname(__FILE__).'/XMLSupported.php';


class Elo implements XMLSupported{
	
	
	public $_id = ''; 
	
	private $_content = '';
	private $_metadata = array();
	private $_resources = array();
	
	
	public function __construct($xml=null){
		if ($xml !== null){
			$this->buildFromXML($xml);
		}
	}
	
	public function getMetadata($key){
		return $this->_metadata[$key];
	}
	
	public function getAllMetadata(){
		return $this->_metadata;
	}
	
	public function getContent (){
		return $this->_content;
	}
	
	public function getContentDataTag(){
		$contentDom = str_get_dom($this->_content);
		$content = $contentDom->find('data');
		$content = $content[0];
		return $content->innertext;
	}
	
	public function setContent($content){
		$content = str_replace('|||', '&', $content);
		$content = html_entity_decode($content);
		$this->_content = $content;
	}
	
	public function addMetadata($key, $value){
		$this->_metadata[$key] = $value;
	}
	
	public function removeMetadata($key){
		unset($this->_metadata[$key]);
	}
	
	public function addResource($key, $value){
		$this->_resources[$key] = $value;
	}
	
	public function removeResource($key){
		unset($this->_resources[$key]);
	}
	
	public function generateXml(){
		$content = $this->genXmlForContent();
		
		/*
		 * generate Metadata
		 */
		$metadata = '';
		foreach ($this->_metadata as $key => $value){
			if ($key == 'uri'){
				$value = '<catalog>scy.collide.info</catalog><entry>' . $value . '</entry>';
			}
			
			$metadata .= "<$key>$value</$key>";
		}
		$metadata = '<metadata>'.$metadata.'</metadata>';
		
		/*
		 * generate Resources
		 */
		$resources = '';
		foreach ($this->_resources as $key => $value){
			$resources .= "<$key>$value</$key>";
		}
		$resources = '<resources>'.$resources.'</resources>';
		
		$xml = '<elo>'.$metadata.$content.$resources.'</elo>'; 
		return $xml;
	}
	
	public function genXmlForContent(){
		$content = '<content contentType="xml"><data>'. htmlentities($this->_content) .'</data></content>';
		$content = str_replace('&', '|||', $content);
		return $content;
	}
	
	/**
	 * Given the xml representation of the elo, build
	 * the elo object from the xml input
	 *
	 * @param unknown_type $eloXML
	 */
	public function buildFromXML($eloXML = null){
		if ($eloXML !== null){
			$dom = str_get_dom($eloXML);
			
			$contentWithDataTag = $this->getElemAtPos($dom, 'content', 0);
			$dataTag = $this->getElemAtPos($contentWithDataTag, 'data', 0); 
			if ($dataTag->innertext !== null){
				$content = $dataTag->innertext;
			}else{
				$content = '';
			}
			
			$this->setContent($content);
			
			$metadataElem = $this->getElemAtPos($dom, 'metadata', 0);
			if ($metadataElem !== null){
				foreach($metadataElem->childNodes() as $curMetadataElem){
					
					if ($curMetadataElem->tag == 'uri'){
						$curMetadataElem = $curMetadataElem->find('entry');
						$curMetadataElem = $curMetadataElem[0];
						
						$key = 'uri';
						
					}else {
						$key = $curMetadataElem->tag;
					}
					
					$value = $curMetadataElem->innertext;
					$this->_metadata[$key] = $value;

					// extract the id from URI
					if ($key === 'uri'){
						$this->_id = substr(strrchr($value, '/'), 1);
					}
					
					// This code is to dynamically set the values for the
					// elo's metadatas
					/*
					 * THIS WAS MESSING UP THE POPULATION OF THE CODE. 
					 * SEEMS TO BE DOING THE EXACT SAME THING AS $this->_metadata[$key] = $value; BUT ADDING A _<mt key> VARIABLE THAT
					 * MESSES UP THE XML GENERATION.
					 */
//					$keyVar = "_" . $key;
//					$this->$keyVar = $value;
//					$this->addMetadata($key, $value);
					
				}
			}
			
			$resourceElem = $this->getElemAtPos($dom, 'resource', 0);
			if ($resourceElem !== null){
				if ($resourceElem ){
					foreach($resourceElem->childNodes() as $curResourceElem){
						$key = $curResourceElem->tag;
						$value = $curResourceElem->innertext;
						
						$this->_resources[$key] = $value;
					}
				}
			}
		}
	}
	
	/**
	 * Searched the $dom using $selector and in the results, it returns the element at position $pos 
	 * If $pos is not provided, it returns all results found
	 * @param simpel_html_dom|simple_html_dom_node $dom
	 * @param string $selector
	 * @param int $pos
	 * @return Either a simple_html_dom_node, or an array(simple_html_dom_node)
	 */
	private function getElemAtPos($dom, $selector, $pos=-1){
		$results = $dom->find($selector);
		if ($pos != -1){
			if (isset($results[$pos])){
				return $results[$pos];
			}else{
				return null;
			}
			
		}else{
			return $results;
		}
	}
	
	
	public function toString(){
		echo $this->generateXml();
	}
	
	public function toArray(){
		$returnArray = array();
		
		$returnArray = $this->_metadata;
		$returnArray['content'] = $this->_content;
		
		return $returnArray;
	}
	
	/**
	 * @return unknown
	 */
	public function get_author() {
		return $this->getMetadata('author');
	}
	
	/**
	 * @return unknown
	 */
	public function get_datecreated() {
		return $this->getMetadata('datecreated');
	}
	
	/**
	 * @return unknown
	 */
	public function get_datelastmodified() {
		return $this->getMetadata('datelastmodified');
	}
	
	/**
	 * @return unknown
	 */
	public function get_title() {
		return $this->getMetadata('title');
	}
	
	/**
	 * @return unknown
	 */
	public function get_type() {
		return $this->getMetadata('type');
	}
	
	public function get_runId(){
		return $this->getMetadata('runid');
	}
	
	/**
	 * @return unknown
	 */
	public function get_uri($escape=false) {
		if ($escape){
			$newUri = $this->getMetadata('uri');
			$newUri = str_replace(':', '\:', $newUri);
			$newUri = str_replace('-', '\-', $newUri);
			return $newUri;
		}
		return $this->getMetadata('uri');
	}
	
	/**
	 * @return unknown
	 */
	public function get_version() {
		return $this->getMetadata('version');
	}
	
	/**
	 * @param unknown_type $_author
	 */
	public function set_author($_author) {
		$this->addMetadata('author', $_author);
	}
	
	/**
	 * @param unknown_type $_dateCreated
	 */
	public function set_datecreated($_dateCreated) {
		$this->addMetadata('datecreated', $_dateCreated);
	}
	
	/**
	 * @param unknown_type $_dateLastModified
	 */
	public function set_datelastmodified($_dateLastModified) {
		$this->addMetadata('datelastmodified', $_dateLastModified);
	}
	
	/**
	 * @param unknown_type $_title
	 */
	public function set_title($_title) {
		$this->addMetadata('title', $_title);
	}
	
	/**
	 * @param unknown_type $_type
	 */
	protected function set_type($_type) {
		$this->addMetadata('type', $_type);
	}
	
	public function set_runId($runId){
		$this->addMetadata('runid', $runId);
	}
	
	/**
	 * @param unknown_type $_uri
	 */
	public function set_uri($_uri) {
		$this->addMetadata('uri', $_uri);
		
	}
	
	/**
	 * @param unknown_type $_version
	 */
	public function set_version($_version) {
		$this->addMetadata('version', $_version);
	}
	
	
	/**
	 * @param unknown_type $_uri
	 */
	public function set_id($_id) {
		$this->_id = $_id;
	}
	
	/**
	 * @return unknown
	 */
	public function get_id() {
		return $this->_id;
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