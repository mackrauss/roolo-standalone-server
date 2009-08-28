<?php

require_once 'domLib/simple_html_dom.php';
require_once 'dataModels/Article.php';

class RooloClient {
	
	private $_rooloUrl = '';
	private $_rooloServiceUrls = array();
	
	public function __construct(){
		$this->_rooloUrl = "http://localhost:8080/roolo-standalone-server/";
		$this->populateServiceUrls();
	}
	
	/**
	 * Constructs URLs for all available services provided by Roolo-standalone-server 
	 *
	 * @return unknown
	 */
	private function populateServiceUrls(){
		$this->_rooloServiceUrls = array('retrieveAll' => $this->_rooloUrl . 'RetrieveAll',
							'retrieveElo' => $this->_rooloUrl . 'RetrieveELO',
							'addElo' => $this->_rooloUrl . 'AddELO',
							'deleteElo' => $this->_rooloUrl . 'DeleteELO',
							'search' => $this->_rooloUrl . 'Search');
		
	}
	
	/**
	 * Given an elo object, add it to the repository
	 * 
	 * If adding causes an error, the error is returned
	 * otherwise false is returned.
	 *
	 * @param unknown_type $eloObj
	 * @return unknown
	 */
	public function addElo($eloObj){
		
		$eloXml = $eloObj->generateXml();

		$eloXml = urlencode($eloXml);
		$url = $this->_rooloServiceUrls['addElo'] . '?eloXML=' . $eloXml;
		$error = file_get_contents($url);
		
		if ($error) {
			return $error;	
		}else {
			return false;
		}
	}
	
	/**
	 * Given an elo's uri, delete it to the repository
	 * 
	 * If deleting causes an error, the error is returned
	 * otherwise false is returned.
	 *
	 * @param unknown_type $eloUri
	 * @return unknown
	 */
	public function deleteElo($eloUri){
		
		$url = $this->_rooloServiceUrls['deleteElo'] . '?uri=' . $eloUri;
		$error = file_get_contents($url);
		
		if ($error) {
			return $error;	
		}else {
			return false;
		}
	}
	
	/**
	 * Given an elo URI, retrieve it from the roolo-server
	 * and convert it to its appropriate ELO object and return it
	 *
	 * @param unknown_type $eloUri
	 * @return unknown
	 */
	public function retrieveElo($eloUri){
		
		$url = $this->_rooloServiceUrls['retrieveElo'] . '?uri=' . $eloUri;
		
		$eloXml = file_get_contents($url);
		return $this->createAppropriateElo($eloXml);
	}
	
	/**
	 * Given a list of elo types, retrieve all the elos
	 * in the database corresponding to those types
	 *
	 * @param unknown_type $eloTypes
	 * @return unknown
	 */
	public function retrieveAll($eloTypes){

		$searchUrl = $this->_rooloServiceUrls['search'] . '?query=';
		
		foreach ($eloTypes as $eloType){
			$searchUrl .= 'TYPE:' . $eloType . ' OR '; 
		}
		
		$searchUrl = substr($searchUrl, 0, strripos($searchUrl, ' OR '));
		
		$elos = file_get_contents($searchUrl);
		return $elos;
	}
	
	/**
	 * Given the XML representation of an elo,
	 * return the object representation
	 *
	 * @param unknown_type $eloXml
	 */
	private function createAppropriateElo($eloXml){
		
		$dom = str_get_dom($eloXml);
		
		$type = $dom->find('metadata type');
		$type = $type[0];
		$type = $type->innertext;
		
		switch ($type) {
			case 'article':
				return new Article($eloXml);				
				break;
			case 'citation':
				return new Citation($eloXml);
				break;
			case 'comment':
				return new Comment($eloXml);
				break;
			case 'lock':
				return new Lock($eloXml);
				break;
			case 'reference':
				return new Reference($eloXml);
				break;
			case 'tag':
				return new Tag($eloXml);
				break;
		}
		
	}

}

?>