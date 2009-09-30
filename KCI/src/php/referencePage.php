<?php
require_once 'dataModels/Elo.php';
require_once 'dataModels/Citation.php';
require_once 'ReferenceCategories.php';
require_once 'RooloClient.php';

$rooloClient = new RooloClient();

if ($_REQUEST['action'] == 'addElo'){
	
	$eloTitle = $_REQUEST['eloName'];
	$eloContent = $_REQUEST['eloContent'];
	
	$citation = new Citation();
	$citation->set_title($eloTitle);
	
	//TODO fill in the author info
	$citation->set_author('rokham');
	$citation->setContent($eloContent);
	//TODO addElo needs to return the URI for the added ELO
	// We need that to add the appropriate tags for the given citation
	echo $rooloClient->addElo($citation);		
	
	//TODO create tags for this citation

	$action = 'update';

} elseif ($_REQUEST['action'] == 'update'){
	
	$eloTitle = $_REQUEST['eloName'];
	$eloContent = $_REQUEST['eloContent'];
	$eloId = $_REQUEST['id'];
	$eloId = $rooloClient->getUriDomain() . $eloId . '.Citation';
	
	//TODO Do we need to escape special characters for our URI 
//	$eloId = str_replace(':', '\:', $eloId);
//	$eloId = str_replace('-', '\-', $eloId);

	$citation = new Citation();
	$citation->set_uri($eloId);
	$citation->set_title($eloTitle);
	
	//TODO fill in the author info
	$citation->set_author('rokham');
	$citation->setContent($eloContent);
	echo $rooloClient->updateElo($citation);
	
	$action = 'update';
} else{

	if (isset($_REQUEST['id'])){
		
		$action = 'update';
		$eloId = $_REQUEST['id'];
		
		$citation = new Citation();
		$citation = $rooloClient->retrieveElo($eloId);
		$citation->set_uri($rooloClient->getUriDomain() . $eloId);
		
		
		//TODO Get the tags corresponding to this Citation
		$citationTagsArray = $rooloClient->search('type:Tag AND owneruri:'. $citation->get_uri(true), 'metadata');
		$citationTagTitles = array();
		foreach ($citationTagsArray as $citationTag){
			$citationTagTitles[] = '"' . $citationTag->get_title() . '"';
 		}
 		if (sizeof($citationTagTitles) > 0){
			$citationTagsString = implode(', ', $citationTagTitles);
 		}else {
 			$citationTagsString = '';
 		}
		
	}else {
		$citation = new Citation();
		$action = 'addElo';
	}
}

?>


<html>


<!--<head>-->
<!---->
<!-- Load TinyMCE -->
<script type="text/javascript" src="/library/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript"><!--
	$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '/tinymce/jscripts/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			//content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
</script>
</head>



<body>
	<form method="post">
		<div style='width: 50%; margin-left: auto; margin-right: auto'>
		
			Title 
			<div style='width: 70%'>
				<input type="text" id="eloName" name="eloName" value="<?= $citation->get_title() ?>" style='width: 70%'/>
			</div>
			
			
			<div style='margin-top: 20px; margin-bottom: 20px; width: 90%;'>
				<label for='eloContent'>Description</label>
				<br/>
				<textarea id="eloContent" name="eloContent" class="tinymce" style='float: left'>
					<?= htmlentities($citation->getContent()) ?>
				</textarea>
			</div>
	
			<!-- Some integration calls -->


			<div style='margin-top: 20px;'>
				<label for='eloTags'>Tags</label>
				<input id='eloTags' type="text" size="100%" value='<?= $citationTagsString?>' style='margin-top: 10px; margin-bottom: 10px; width 100%; float: left'>
			</div>		
	
			<div style='margin-top:1px; float: right'>
				<input type="submit" name="save" value="Submit"/>
				<input type="reset" name="reset" value="Reset"/>
			</div>
			
			
		</div>
		
		<input type="hidden" id="action" name="action" value="<?= $action?>" />
		<input type="hidden" id="eloId" name="eloId" value="<?=$_REQUEST['id'] ?>" />
		<input type="hidden" id="eloVersion" name="eloVersion" value="<?=$citation->get_version() ?>" />
	</form>
</body>

</html>
