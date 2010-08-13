<?php

interface XMLSupported {
	
	public function generateXML();
	
	public function buildFromXML($eloXML=null);
}

?> 