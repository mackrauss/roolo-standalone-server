<?php
require_once dirname(__FILE__).'/Elo.php';

class SuperSummary extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'SuperSummary');
	}
	
	public function set_problemUri($uri){
		parent::addMetadata('problemUri', $uri);
	}
	
	public function set_summary($summary){
		parent::addMetadata('summary', $summary);
	}
	
	public function set_solutionUris($uri){
		parent::addMetadata('solutionUris', $uri);
	}
	
	public function set_forChoice($forChoice){
		parent::addMetadata('forChoice', $forChoice);	
	}
}
?>