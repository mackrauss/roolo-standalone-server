<?php

require_once dirname(__FILE__).'/Elo.php';

class Comment extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Comment');
	}

}

?>