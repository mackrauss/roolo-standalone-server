<?php
require_once dirname(__FILE__).'/Elo.php';

class PersonalReflection extends Elo {

	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'PersonalReflection');
	}
 
	public static function getSections(){
		return array(
					'thoughts' => 'Thoughts',
					'criticism' => 'Criticism',
					'activityEffect' => 'Activity Effect'
				);
	}
}

?>