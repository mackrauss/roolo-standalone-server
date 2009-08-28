<?php

require_once '../../domLib/simple_html_dom.php';

class RooloClient {
	
	private $_rooloUrl = '';
	private $_rooloServiceUrls = array();
	
	public function __construct(){
		$this->_rooloUrl = "http://localhost:8080/roolo-standalone-server/";
		$this->_rooloServiceUrls = $this->populateServiceUrls();
	}
	
	
	/**
	 * Constructs URLs for all available services provided by Roolo-standalone-server 
	 *
	 * @return unknown
	 */
	private function populateServiceUrls(){
		$serviceUrls = array('retrieveAll' => $this->_rooloUrl . 'RetrieveAll',
							'addElo' => $this->_rooloUrl . 'AddELO',
							'deleteElo' => $this->_rooloUrl . 'DeleteELO',
							'search' => $this->_rooloUrl . 'Search');
		return $serviceUrls;
		
	}
	
	public function retrieveAll($eloTypes){

		$searchUrl = $this->_rooloServiceUrls['search'] . '?query=';
		
		foreach ($eloTypes as $eloType){
			$searchUrl .= 'TYPE:' . $eloType . ' OR '; 
		}
		
		$searchUrl = substr($searchUrl, 0, strripos($searchUrl, ' OR '));
		
		$elos = file_get_contents($searchUrl);
		return $elos;
	}

}

?>