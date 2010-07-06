<?php
require_once dirname(__FILE__).'/Elo.php';

class LongAnswer extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'LongAnswer');
	}
	
	public function __get($name) {
		return parent::getMetadata($name);
    }
	
    public function set_ownerUri($ownerUri){
    	parent::addMetadata('owneruri', $ownerUri);
    }
    
    public function set_questionPath($questionPath){
		parent::addMetadata('questionpath', $questionPath);    	
    }
    
    public function set_conceptQuestionUri($uri){
    	parent::addMetadata('conceptquestionuri', $uri);
    }
    
    public function set_category($category){
    	parent::addMetadata('category', $category);
    }
    
    public function set_formulas($formulas){
    	parent::addMetadata('formulas', $formulas);
    }
}
