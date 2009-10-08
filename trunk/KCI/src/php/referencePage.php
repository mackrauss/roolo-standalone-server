<?php
require_once 'ReferenceCategories.php';
require_once 'RooloClient.php';
require_once 'dataModels/Reference.php';

require_once './header.php';

error_reporting(E_STRICT);

$rooloClient = new RooloClient();


if ($_REQUEST['action'] == 'addElo'){
	// Add the submitted Reference to Roolo
	$submittedReference = new Reference();
	//get the author from the session
	//$submittedReference->set_author($_SESSION['user']);
	$submittedReference->set_title($_POST["referenceTitle"]);
	$submittedReference->set_annotation($_POST["annotationText"]);
	$submittedReference->set_citation($_POST["citationText"]);
	
	//echo "reference Category =".$_POST["referenceCategoryList"];
	$submittedReference->set_category($_POST["referenceCategoryList"]);
	$submittedReference->set_datecreated('');
	$submittedReference->set_uri('');
	$submittedReference->set_version('');

	$reference = new Reference($rooloClient->addElo($submittedReference));
	$ownerUri = $reference->get_uri();

	$tagsArray = array_unique(explode (',', trim($_POST["tags"])));
	foreach ($tagsArray as $tagTitle){
		$tagObject = new Tag();
		$tagObject->set_ownerType("Reference");
		$tagObject->set_ownerUri($ownerUri);
		$tagObject->set_title(trim($tagTitle));
		$tagObject->set_uri('');
		$tagObject->set_version('');
		$rooloClient->addElo($tagObject);
	}
	
	$action = 'update';
	//$reference = new Reference($submittedReference->generateXml());

} else if ($_REQUEST['action'] == 'update') {
	// Updte the submitted Reference in Roolo
	echo 'updated';
	// need to fill this object with all the send in params of the form
	$reference = new Reference();
	
	$action = 'update';
}else{

	if (isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
		$eloId = $rooloClient->getUriDomain() . $id;
		$reference = new Reference();
		$reference = $rooloClient->retrieveElo($eloId);

		// get all the tags for the current reference
		$tags = $rooloClient->search('type:Tag AND owneruri:'.$reference->get_uri(true), 'metadata');
		$tagsArray = array();
		foreach ($tags as $tag){
			$tagsArray[] = $tag->get_title();
		}
		$refernceTagsString = implode(', ', $tagsArray);

		$action = 'update';
	}else {
		$reference = new Reference();
		$action = 'addElo';
	}
}

?>
		<script type='text/javascript' src='../../library/js/jquery-1.3.2.min.js'></script>
		<script type='text/javascript' src='../../library/js/jquery-ui-1.7.2.custom.min.js'></script>
		<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
		<title>References Page</title>
		<style>
			#titleArea { 
	 	 		width:80%;
				padding:10px 0px 20px 0px;
	 	 		margin-left: auto;
				margin-right: auto;
			}
			#anotationArea { 
	 	 		width:80%;
				padding:40px 0px 0px 150px; 
	 	 		margin-left: auto;
				margin-right: auto;
			}
			#citationArea { 
	 	 		width:80%;
				padding:20px 0px 0px 150px; 
	 	 		margin-left: auto;
				margin-right: auto;
			}
			#tagArea { 
	 	 		width:80%;
				padding:20px 0px 0px 150px; 
	 	 		margin-left: auto;
				margin-right: auto;
			}
			#submitArea { 
	 	 		width:80%;
				padding:20px 0px 0px 0px; 
	 	 		margin-left: auto;
				margin-right: auto;
				text-align: right;
			}
			#title { 
	 	 		width:60%;
				padding:0px; 
	 	 		margin-left: 0%;
				margin-right: auto;
				float: left;
			}
			#select { 
	 	 		margin-right: 14%;
				float: right;
			}
			
			.title{
				float: left;
				width: 35%;
				text-align: left;
				font-family:Ariel, Verdana, Sans-serif;
				font-style:italic;
				font-size:17px;
				color:#0300ee;
			}
			
			.myButton { 
			   font-family: Arial, Helvetica, sans-serif; 
			   font-size: 20px; 
			   background-color: #bcffaf;
			   margin-top: 2%;
			   margin-right: 20%; 
			   padding: 0.1%; 
			   height: 30px; 
			   width: 200px;
			   float: right;
			   border: 1px solid #000000;
			   -moz-border-radius-topleft: 50px; 
			   -moz-border-radius-topright: 50px; 
			   -moz-border-radius-bottomleft: 50px; 
			   -moz-border-radius-bottomright: 50px;  
			} 
			
	    </style>
		
		<script type='text/javascript'>

			// disable the form submission, if the user accidentaly
			// hits the enter button, while the cursor is in a textbox. 
			// There is no need to do this for TextAreas
			function disableEnterKey(e)
			{
			     var key;      
			     if(window.event)
			          key = window.event.keyCode; //IE
			     else
			          key = e.which; //firefox      
			     return (key != 13);
			}

		</script>
	</head>
	
	
	<body>
	
		<a href='http://localhost/src/php/referencesPage.php'> <?= htmlspecialchars(' < Back to References')  ?> </a>
		
		<form method="post" action="referencePage.php">
				<div id='titleArea'>
					<div id='title'>
						<font size='4'>Reference Title</font>
						<input type="text" id="referenceTitle" name="referenceTitle" onKeyPress="return disableEnterKey(event)" style="width:300px" value="<?= $reference->get_title() ?>"/>
					</div>
					<div id='select'>
						<select id="referenceCategoryList" name="referenceCategoryList"> 
							<?php 
								$referenceCategories = new ReferenceCategories();
								$refCatsList = $referenceCategories->getReferenceCategories(); 
								
								// Fill in the reference's category
								if (isset($_REQUEST['id'])){
									$result = "<option value=''>" . $reference->get_category() . "</option>";
								}else {
									$result = "<option value=''> Select a Category </option>";									
								}
								for ($i = 0; $i < sizeof($refCatsList); $i++){
									//$result .= "<option value='".$i."'>".$refCatsList[$i]."</option>";
									$result .= "<option value='".$refCatsList[$i]."'>".$refCatsList[$i]."</option>";
								}
								echo $result;
							?>
						</select>
					</div>
		  		</div>
		  		
				<div id='anotationArea' >
					<font size='4'>Annotation</font><br/>
					<textarea id="annotationText" name="annotationText" rows='10' cols='75' id='anotation'> <?= $reference->get_annotation() ?></textarea>
				</div>
				
				<div id='citationArea'>
					<font size='4'>Citation</font> <br/>
					<textarea id="citationText" name="citationText" rows='10' cols='75' id='citation'> <?= $reference->get_citation() ?> </textarea>
				</div>

				<div id='tagArea' >
					<div id='tag'>
						<font size='4'>Tags</font><br/>
						<input type="text" id="tags" name="tags" onKeyPress="return disableEnterKey(event)" style="width:600px; height: 100px;" value="<?= $refernceTagsString?>" />
					</div>
				</div>
				
				<div style='width: 100%; height: 20%; float:left'>
					<input type="hidden" id="action" name="action" value="<?= $action?>" />
					<input type="hidden" id="id" name="id" value="<?= $reference->get_id() ?>" />
					<input type="submit" value='Submit' class='myButton'>
				</div>
				
				
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				
	 	</form>	
	
	
<?php 
	require_once 'footer.php';
?>
	