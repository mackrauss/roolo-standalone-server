<?php
require_once dirname(__FILE__).'/Elo.php';

class SuperSummary extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'SuperSummary');
	}
	
	public function set_problemUri($uri){
		parent::addMetadata('problemuri', $uri);
	}
	
	public function get_problemUri(){
		parent::getMetadata('problemuri');
	}
	
	public function set_summary($summary){
		parent::addMetadata('summary', $summary);
	}
	
	public function get_summary(){
		parent::getMetadata('summary');
	}
	
	public function set_solutionUris($uri){
		parent::addMetadata('solutionuris', $uri);
	}
	
	public function get_solutionUris(){
		parent::getMetadata('solutionuris');
	}
	
	public function set_forChoice($forChoice){
		parent::addMetadata('forchoice', $forChoice);	
	}
	
	public function get_forChoice(){
		parent::getMetadata('forchoice');	
	}
}
?>