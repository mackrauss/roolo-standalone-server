<?php

require_once dirname(__FILE__).'/Elo.php';

class Comment extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Comment');
	}
	
	/** 
	 * @return unknown
	 */
	public function get_ownerType() {
		return parent::getMetadata('ownertype');
	}
	
	/**
	 * @return unknown
	 */
	public function get_ownerUri() {
		return parent::getMetadata('owneruri');
	}
	
	/**
	 * @param unknown_type $_ownerType
	 */
	public function set_ownerType($_ownerType) {
		parent::addMetadata('ownertype', $_ownerType);
	}
	
	/**
	 * @param unknown_type $_ownerUri
	 */
	public function set_ownerUri($_ownerUri) {
		parent::addMetadata('owneruri', $_ownerUri);
	}
	
	public function generateHtml(){
		$roolo = new RooloClient();
		
		$commentText = $roolo->decodeContent($this->get_title());
//		$commentText = $this->get_title();
		
		$o = '';
		
		$o .= "<div style='margin-bottom: 10px;'>";
		$o .= '<span style="font-size:small"> By '.htmlspecialchars($this->get_author()).' on '.date('F jS', $this->get_dateCreated()/1000). ' </span><br/>';
		$o .= $commentText;
		$o .= '</div>';
		return $o;
	}

}

?>