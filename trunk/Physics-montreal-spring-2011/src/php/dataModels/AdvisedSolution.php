<?php
require_once dirname(__FILE__).'/Elo.php';
require_once dirname(__FILE__).'/Solution.php';

class AdvisedSolution extends Solution {

	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'AdvisedSolution');
	}
}
?>
