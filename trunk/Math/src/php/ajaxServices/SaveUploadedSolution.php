<?php
require_once '../RooloClient.php';
require_once '../dataModels/Elo.php';
require_once '../dataModels/UploadedSolution.php';

class SaveUploadedSolution {
	
	private $_author;
	private $_questionUri;
	function __construct() {
	
	}
	/**
	 * Save the uploded file in answers directory
	 *
	 * @return unknown
	 */
	public function saveUploadedFile($uploadedFile){

		if((!empty($uploadedFile)) && ($uploadedFile['error'] == 0)) {
			//Directory for saving uploaded file
			$uploaddir = dirname(__FILE__).'/../../../Answers/';

			//assign an integer number dedicated the saved time of uploaded file to the name of saved file 
			$now = time();
			$extention = strrchr( basename($uploadedFile['name']),'.');
			$fileName = $now . $extention;
			$file = $uploaddir . $fileName;
			
		    if (move_uploaded_file($uploadedFile['tmp_name'], $file)) {
		    	
		    	$this->save($fileName);
		    	return  "Your solution was saved successfuly";
		    	
		    } else {  
		        return  "We encountered a problem in saving your solution named " . $uploadedFile['name']. ". Please ask one of the staff to help you.";  
		    }
		}else {
			return "No file was chosen.";
		}  
	}
	
	/**
	 *create and save in repository uploadSolution Object
	 */
    private function save($fileName){
 		
    	$questionURIShortened = substr($this->_questionUri, strrpos($this->_questionUri, '/')+1);
    	
		$rooloClient = new RooloClient();
		// Creating a UploadedSolution elo. 
		$uploadedSolution = new UploadedSolution();
		$uploadedSolution->set_title($this->_author . "-" . $questionURIShortened . '-answer');
		$uploadedSolution->set_author($this->_author);
		$uploadedSolution->set_ownerUri($this->_questionUri);

		$filePath = '/Answers/'. $fileName;
		$uploadedSolution->set_path($filePath);
		$rooloClient->addElo($uploadedSolution);
    }
    
    public function set_author($author){
    	$this->_author = $author;
    }
    public function set_questionUri($questionUri){
    	$this->_questionUri = $questionUri;
    }
    
}
?>