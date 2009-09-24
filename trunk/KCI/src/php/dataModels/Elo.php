<?php

require_once 'domLib/simple_html_dom.php';
require_once 'XMLSupported.php';

//$elo1 = new Elo();
//$elo1->setContent('<p>hello world, this is my blow me.</p>');
//$elo1->addMetadata('title', 'elo title');
//$elo1->addMetadata('type', 'question');
//$elo1->addMetadata('keywords', 'life death beyond');
//$elo1->addMetadata('author', 'aajellu');
//$elo1->addMetadata('description', 'this elo is meant to blow your mind');
//echo $elo1->generateXml();

//$xml = '<elo><metadata><title>elo title</title><type>question</type><keywords>life death beyond</keywords><author>aajellu</author><description>this elo is meant to blow your mind</description></metadata><content>hello world, this is my freaking content. blow me.</content><metadata></metadata></elo>';
//$elo1 = new Elo($xml);
//echo $elo1->generateXml();
//echo $elo1->getContent();

class Elo implements XMLSupported{
	private $_content = '';
	private $_metadata = array();
	private $_resources = array();
	
	public function __construct($xml=null){
		if ($xml !== null){
			$xml = str_replace('|||', '&', $xml);
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
		//$content = '<content contentType="xml"><data>'.addslashes($this->_content).'</data></content>';
		$content = '<content contentType="xml"><data>'.$this->_content.'</data></content>';
		
		$content = str_replace('&', '|||', $content);
		
		/*
		 * generate Metadata
		 */
		$metadata = '';
		foreach ($this->_metadata as $key => $value){
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
	
	/**
	 * Given the xml representation of the elo, build
	 * the elo object from the xml input
	 *
	 * @param unknown_type $eloXML
	 */
	public function buildFromXML($eloXML = null){
		if ($eloXML !== null){
			$dom = str_get_dom($eloXML);
			
			$this->_content = $this->getElemAtPos($dom, 'content', 0)->innertext;
			
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
}