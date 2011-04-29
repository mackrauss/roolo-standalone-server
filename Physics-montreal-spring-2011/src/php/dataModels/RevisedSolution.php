<?php
require_once dirname(__FILE__).'/Solution.php';

class RevisedSolution extends Solution {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'RevisedSolution');
	}
}