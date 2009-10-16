<?php
require_once dirname(__FILE__).'/Elo.php';

class Tag extends Elo {
	
	public function __construct($xml=null){
		parent::__construct($xml);
		parent::addMetadata('type', 'Tag');
	}
	
	public function get_status(){
		return parent::getMetadata('status');
	}
	 
	/**
	 * @return unknown
	 */
	public function get_dateDeleted() {
		return parent::getMetadata('datedeleted');
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
	
	
	public function set_status($status){
		parent::addMetadata('status', $status);
	}
	
	/**
	 * @param unknown_type $_dateDeleted
	 */
	public function set_dateDeleted($_dateDeleted) {
		parent::addMetadata('datedeleted', $_dateDeleted);
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
	
	public static function generateHtml($tagUri, $tagTitle){
		$divId = $tagUri.'_tag_div';
		
		$o = '';
		$o .= "<div name='$divId' id='$divId' class='tag' style='float:left; vertical-align: middle;'>";
		$o .= $tagTitle."&nbsp;<img src='/src/images/cross.png' width='10px' height='10px' onclick=\"return removeSectionTag('$tagUri', this)\" />";
		$o .= " </div>\n";
		
		return $o;
	}
}

?>