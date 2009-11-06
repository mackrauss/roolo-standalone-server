<?php
session_start();
require_once './header.php';
require_once 'ReferenceCategories.php';
require_once 'RooloClient.php';
require_once 'dataModels/Reference.php';
require_once 'util/TagUtil.php';


error_reporting(E_STRICT);

$rooloClient = new RooloClient();

$tags = array();
 

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
	
	//$tags = array_unique(explode (',', trim($_POST["tags"])));
	if (trim($_REQUEST['tags']) !== ''){
		
		$tagsArray = array_unique(explode (',', trim($_REQUEST["tags"])));
		foreach ($tagsArray as $tagTitle){
			
			$query = "type:Tag AND owneruri:". $reference->get_uri(true) . " AND title:$tagTitle AND status:active";
			$results = $rooloClient->search($query, 'metadata', 'latest');
			if (sizeof($results) == 0){
				$tagObject = new Tag();
				$tagObject->set_ownerType("Reference");
				$tagObject->set_ownerUri($ownerUri);
				$tagObject->set_title(trim($tagTitle));
				$tagObject->set_uri('');
				$tagObject->set_version('');
				$tagObject->set_status('active');
				$rooloClient->addElo($tagObject); 
			}
		}
	}
	
	$tags = $rooloClient->search('type:Tag AND owneruri:'.$reference->get_uri(true) . ' AND status:active', 'metadata', 'latest');
	$action = 'update';
	echo "<script type='text/javascript'> window.location.href='/src/php/referencesPage.php'; </script>";
	die();

} else if ($_REQUEST['action'] == 'update') {
	// update the submitted Reference to Roolo
	$newVersionReference = new Reference();
	//get the author from the session
	//$newVersionReference->set_author($_SESSION['user']);
	$newVersionReference->set_title($_POST["referenceTitle"]);
	$newVersionReference->set_annotation($_POST["annotationText"]);
	$newVersionReference->set_citation($_POST["citationText"]);
	
	$newVersionReference->set_category($_POST["referenceCategoryList"]);
	$newVersionReference->set_uri($rooloClient->getUriDomain() . $_POST["id"]);
	$reference = new Reference($rooloClient->updateElo($newVersionReference));
	
	$ownerUri = $reference->get_uri();
	
	$tagsArray = array_unique(explode (',', trim($_POST["tags"])));
	foreach ($tagsArray as $tagTitle){
		
		$query = "type:Tag AND owneruri:". $reference->get_uri(true) . " AND title:$tagTitle AND status:active";
		$results = $rooloClient->search($query, 'metadata', 'latest');
		if (sizeof($results) == 0){
			$tagObject = new Tag();
			$tagObject->set_ownerType("Reference");
			$tagObject->set_ownerUri($ownerUri);
			$tagObject->set_title(trim($tagTitle));
			$tagObject->set_uri('');
			$tagObject->set_version('');
			$tagObject->set_status('active');
			$rooloClient->addElo($tagObject); 
		}
	}
	$tags = $rooloClient->search('type:Tag AND owneruri:'.$reference->get_uri(true) . ' AND status:active', 'metadata', 'latest');
	$action = 'update';
	echo "<script type='text/javascript'> window.location.href='/src/php/referencesPage.php'; </script>";
	die();
}else{

	if (isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
		$eloId = $rooloClient->getUriDomain() . $id;
		$reference = new Reference();
		$reference = $rooloClient->retrieveElo($eloId);

		// get all the tags for the current reference
		$tags = $rooloClient->search('type:Tag AND owneruri:'.$reference->get_uri(true) . ' AND status:active', 'metadata', 'latest');

		$action = 'update';
	}else {
		$reference = new Reference();
		$action = 'addElo';
	}
}

?>
		<script type='text/javascript' src='../../library/js/jquery-1.3.2.min.js'></script>
		<script type='text/javascript' src='../../library/js/jquery-ui-1.7.2.custom.min.js'></script>
		
		<script type='text/javascript' src='/src/js/popup.js'></script>
		<link rel="stylesheet" type="text/css" href="/src/css/popup.css" />
		
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
				padding:30px 0px 0px 150px; 
	 	 		margin-left: auto;
				margin-right: auto;
			}
			#citationArea { 
	 	 		width:80%;
				padding:20px 0px 0px 150px; 
	 	 		margin-left: auto;
				margin-right: auto;
			}
			#newTags { 
	 	 		width:80%;
				padding:20px 0px 0px 150px; 
	 	 		margin-left: auto;
				margin-right: auto;
			}
			
			#currentTags{
				width:60%;
				padding:20px 0px 0px 0px; 
	 	 		margin-left: 18%;
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


			function removeSectionTag(tagUri, linkElem){
				$.post('/src/php/ajaxServices/removeTag.php', {'tagUri': tagUri}, 
						function(data){
							$(linkElem).parent().hide(400);
						}
				);
				
				return false;
			}
		
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


			function submitForm(){
				loadPopup(); 
				//$('#' + formName).submit();
				document.referenceForm.submit(); 
			}
			
		</script>
	</head>
	
	
	<body>
	
		<a href='/src/php/referencesPage.php'> <?= htmlspecialchars(' < Back to References')  ?> </a>
		<form name="referenceForm" method="post" action="referencePage.php?id=<?= $reference->get_id();?>">
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
									$result = "<option value='".$reference->get_category()."'>" . $refCatsList[$reference->get_category()] . "</option>";
								}else {
									$result = "<option value=''> Select a Category </option>";									
								}
								foreach ($refCatsList as $refKey => $refVal){
									//$result .= "<option value='".$i."'>".$refCatsList[$i]."</option>";
									$result .= "<option value='".$refKey."'>".$refVal."</option>";
								}
								echo $result;
							?>
						</select>
					</div>
		  		</div>
		  		
				<div id='anotationArea' >
					<font size='4'>Annotation</font><br/>
					<textarea id="annotationText" name="annotationText" rows='10' cols='75' id='anotation'><?= $reference->get_annotation() ?></textarea>
				</div>
				
				<div id='citationArea'>
					<font size='4'>Citation</font> <br/>
					<textarea id="citationText" name="citationText" rows='10' cols='75' id='citation'><?= $reference->get_citation() ?></textarea>
				</div>
				
				<div id='newTags' >
					<div id='tag'>
						<font size='4'>Tags</font><br/>
						<input type="text" id="tags" name="tags" onKeyPress="return disableEnterKey(event)" style="width:600px; "/>
					</div>
				</div>
				
				<div id='currentTags'>
				
					<?php 
						$tagResult = '';
						foreach($tags as $tag){
							
							$curTagUri = $tag->get_uri();
							$tagResult .= Tag::generateHtml($curTagUri, $tag->get_title());
						}
						echo $tagResult;
					?>
				
				</div>

				
				<div style='width: 100%; height: 20%; float:left'>
					<input type="hidden" id="action" name="action" value="<?= $action?>" />
					<input type="hidden" id="id" name="id" value="<?= $reference->get_id() ?>" />
<!--					<input type="submit" value='Submit' class='myButton'>-->
					<input type="button" value='Submit' class='myButton' onClick="submitForm();">
				</div>
				
				
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				
	 	</form>	



<div id="centerPopup">
	<img src="/src/images/loader.gif" name="loader" id='image'/>
</div>
<div id="backgroundPopup"></div>	
	
<?php 
	require_once 'footer.php';
?>
	
	