<?php
require_once dirname(__FILE__).'/domLib/simple_html_dom.php';
require_once dirname(__FILE__).'/dataModels/Elo.php';
require_once dirname(__FILE__).'/dataModels/Tag.php';

class RooloClient {
	
	private $_uriDomain = 'roolo://scy.collide.info/scy-collide-server/';
	private $_rooloUrl = '';
	private $_rooloServiceUrls = array();
	
	public function __construct(){
		#$this->_rooloUrl = "http://localhost:8080/roolo-standalone-server/";
        $this->_rooloUrl = "http://bobek.oise.utoronto.ca:8080/roolo-standalone-server/";
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
							'updateElo' => $this->_rooloUrl . 'UpdateELO',
							'search' => $this->_rooloUrl . 'Search',
							'deleteAll' => $this->_rooloUrl . 'DeleteAll');
		
	}
	
	
	
	public function getUriDomain(){
		return $this->_uriDomain;
	}
	
	public function deleteAll(){
		$url = $this->_rooloServiceUrls['deleteAll'];
		return file_get_contents($url);
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
		return file_get_contents($url);
	}
	
	/**
	 * Given an elo object, update it to the repository
	 * 
	 * If updating causes an error, the error is returned
	 * otherwise false is returned.
	 *
	 * @param unknown_type $eloObj
	 * @return unknown
	 */
	public function updateElo($eloObj){
		
		$eloXml = $eloObj->generateXml();
		$eloXml = urlencode($eloXml);
		
		$url = $this->_rooloServiceUrls['updateElo'] . '?eloXML=' . $eloXml;
		return file_get_contents($url);
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
		
		$eloUri = $this->_uriDomain . $eloUri;
		$eloUri = urlencode($eloUri);
		$url = $this->_rooloServiceUrls['deleteElo'] . '?uri=' . $eloUri;
		return file_get_contents($url);
	}
	
	/**
	 * Given an elo URI, retrieve it from the roolo-server
	 * and convert it to its appropriate ELO object and return it
	 *
	 * @param unknown_type $eloUri
	 * @return unknown
	 */
	public function retrieveElo($eloUri){
		
		//$eloUri = $this->_uriDomain . $eloUri;
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
	 * Given a LUCENE query, retrieve ELOs based on the type and scope
	 * parameters and convert the results to PHP data model objects
	 *
	 * @param String $query Lucene query to be run against Roolo
	 * @param String $resultType Whether the returned value(s) should hold only the URIs of the elos or the entire elos (default: 'uri')
	 * @param String $searchScope Whether the search should be performed on all versions of all ELOs or only their latest versions (default: 'all')
	 */
	public function search ($query=null, $resultType='uri', $searchScope='all'){
		
		if ($query == null){
			return null;
		}
		
		$query = urlencode($query);
		
		$searchUrl = $this->_rooloServiceUrls['search'] . '?query=' . $query . '&resultType=' . $resultType . '&searchScope=' . $searchScope;
		
//		echo $searchUrl . "<br/>";

		$xmlResults = file_get_contents($searchUrl);

		
		$elos = array();
		if ($resultType == 'uri'){
			$elos = $this->parseEloUris($xmlResults);
		}else {
			$elos = $this->parseElos ($xmlResults);
		}
		
		return $elos;
	}
	
	/**
	 * Given XML representation of a set of ELO URIs and their versions, extract
	 * each item an return an array of the collection
	 *
	 * @param unknown_type $xmlResults
	 */
	private function parseEloUris($xmlResults){
		$resultsDom = str_get_dom($xmlResults);
		$resultElos = array();
		
		$xmlElos = $resultsDom->find('SearchResult');
		foreach ($xmlElos as $xmlElo){
			$uri = $xmlElo->find('uri');
			$version = $xmlElo->find('version');
			
			$elo = new Elo();
			$elo->addMetadata('uri', $uri[0]->innertext);
			$elo->addMetadata('version', $version[0]->innertext);
			
			$resultElos[] = $elo;
 		}
 		return $resultElos;
	}
	
	/**
	 * Given XML representation of a set of ELOs, extract
	 * each elo and return them in an array
	 *
	 * @param unknown_type $xmlResults
	 */
	private function parseElos($xmlResults){
		$resultsDom = str_get_dom($xmlResults);
		$resultElos = array();
		
		$xmlElos = $resultsDom->find('elo');
		foreach ($xmlElos as $xmlElo){
			
			$eloType = $xmlElo->find('type');
			$eloType = $eloType[0]->innertext;
			include_once(dirname(__FILE__).'/dataModels/'.$eloType.'.php');
			
			$elo = new $eloType($xmlElo->innertext);
			$resultElos[] = $elo;
 		}
 		return $resultElos;
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

		include_once(dirname(__FILE__).'/dataModels/'.$type.'.php');
		$elo = new $type($eloXml);
//		$eloUri = $elo->get_uri();
//		$eloUri = str_replace($this->_uriDomain, '', $eloUri);
		//$eloUri = str_replace('.'. $type, '', $eloUri);
//		$elo->set_uri($eloUri);
		
		return $elo;
		
	}
	
	public function escapeUri($uri){
		return $this->escapeSearchTerm($uri);
	}
	
	/**
	 * Extracted from http://lucene.apache.org/java/2_3_2/queryparsersyntax.html
	 */
	public function escapeSearchTerm($term){
		$term = str_replace('\\', '\\\\', $term); //never move this down. The precendence is very important here
		$term = str_replace('+', '\+', $term);
		$term = str_replace('-', '\-', $term);
		$term = str_replace('&&', '\&&', $term);
		$term = str_replace('||', '\||', $term);
		$term = str_replace('!', '\!', $term);
		$term = str_replace('(', '\(', $term);
		$term = str_replace(')', '\)', $term);
		$term = str_replace('{', '\{', $term);
		$term = str_replace('}', '\}', $term);
		$term = str_replace('[', '\[', $term);
		$term = str_replace(']', '\]', $term);
		$term = str_replace('^', '\^', $term);
		$term = str_replace('"', '\"', $term);
		$term = str_replace('~', '\~', $term);
		$term = str_replace('*', '\*', $term);
		$term = str_replace('?', '\?', $term);
		$term = str_replace(':', '\:', $term);
		
		return $term;
	}
	
	public function encodeContent($content){
		$content = htmlspecialchars($content);
		$content = str_replace('&', '|||', $content);
		return $content;
	}
	
	public function decodeContent($content){
		$content = str_replace('|||', '&', $content);
		$content = htmlspecialchars_decode($content);
		
		return $content;
	}

}

?>