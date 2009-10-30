<?php

class ReferenceCategories {
	
	private $_referenceCategories;
	
	public function __construct(){
		$this->_referenceCategories = array("c1" => "History of Global Change", "c2" => "Human Activities Impacts", "c3" => "Greenhouse effect", 
											"c4" => "Evidence for climate change", "c5" => "Extreme Weather Events", "c6" => "Weather and wind patterns", 
											"c7" => "Ocean circulation and related", "c8" => "Desertification", "c9" => "Biological effects", "Albedo",
											"c10" => "Carbon Tax, Cap and Trade", "c11" => "Carbon Sinks", "c12" => "Acid Rain", "c13" => "Ocean Acidification", "c14" => "Phenology");
	}
	
	public function getReferenceCategories(){
		return $this->_referenceCategories;
	}
}

?> 