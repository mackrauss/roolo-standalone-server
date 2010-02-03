<?php
	require_once '../RooloClient.php';
	require_once '../dataModels/Elo.php';
	require_once '../dataModels/UploadedSolution.php';

	//Directory for saving uploaded file
	$uploaddir = dirname(__FILE__).'/../../../Answers/';
	
	//assign an integer number dedicated the saved time of uploaded file to the name of saved file 
	$now = time();
	$extention = strrchr( basename($_FILES['uploadedfile']['name']),'.');
	$fileName = $now . $extention;
	$file = $uploaddir . $fileName;
	
    if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $file)) {
    	  
    	echo "success";
    	save($fileName);
    	
    } else {  
        echo "error";  
    }  

    // create and save in repository uploadSolution Object
    function save($fileName){
    	$author = $_POST['author'];
		$ownerURI = $_POST['ownerURI'];

		//$results = '';
		$rooloClient = new RooloClient();
		// Creating a UploadedSolution elo. 
		$uploadedSolution = new UploadedSolution();
		$uploadedSolution->set_title($author . '/answer');
		$uploadedSolution->set_author($author);
		$uploadedSolution->set_ownerUri($ownerURI);

		$filePath = '/Answers/'. $fileName;
		$uploadedSolution->set_path($filePath);
		//$results = $rooloClient->addElo($uploadedSolution);
		$rooloClient->addElo($uploadedSolution);
    }
?>