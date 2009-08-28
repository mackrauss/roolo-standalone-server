<?php
require_once 'dataModels/Elo.php';

//print_r($_REQUEST);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] :'addElo';

//echo "\nCONTENT PASSED: |".$_REQUEST['content']."|\n";

if (isset($_REQUEST['action'])){
	
	if ($_REQUEST['action'] == 'addElo'){
		$eloTitle = $_REQUEST['eloName'];
		$eloContent = $_REQUEST['eloContent'];
//		$eloContent = str_replace('/', '', $eloContent);
		
		$eloObj = new Elo();
		$eloObj->setContent($eloContent);
		$eloObj->addMetadata('title', $eloTitle);
		$eloObj->addMetadata('uri', '/myUri');
		$eloObj->addMetadata('type', 'issue');
		$eloObj->addMetadata('author', 'rokham');
		$eloObj->addMetadata('subject', 'elo');
		$eloObj->addMetadata('gradelevel', 'gradelevel 5');
		$eloObj->addMetadata('familytag', 'familytag R');
		$eloObj->addMetadata('iscurrent', 'iscurrent yest');
		$eloObj->addMetadata('version', '1.0');
		
//		echo "CONTENT INSIDE OBJECT: |".$eloObj->getContent()."|";
		
		$eloObjXml = $eloObj->generateXml();
		
		$addElo = "http://localhost:8080/roolo-standalone-server/AddELO?eloXML=".$eloObjXml;
		$msg = file_get_contents($addElo);
		echo $msg;
		
		$action = 'update';
		echo "<script type='text/javascript' language='javascript'>document.location.href='/src/allIssuesPage.php';</script>";
		flush();
	} elseif ($_REQUEST['action'] == 'update'){
		
		$eloTitle = $_REQUEST['eloName'];
		$eloContent = $_REQUEST['eloContent'];
		$eloId = $_REQUEST['eloId'];
		$eloVersion = $_REQUEST['eloVersion'];
		
		$eloObj = new Elo();
		$eloObj->setContent($eloContent);
		$eloObj->addMetadata('title', $eloTitle);
		$eloObj->addMetadata('uri', $eloId);
		$eloObj->addMetadata('type', 'issue');
		$eloObj->addMetadata('author', 'rokham');
		$eloObj->addMetadata('subject', 'elo');
		$eloObj->addMetadata('gradelevel', 'gradelevel 5');
		$eloObj->addMetadata('familytag', 'familytag R');
		$eloObj->addMetadata('iscurrent', 'iscurrent yest');
		$eloObj->addMetadata('version', $eloVersion);
		
		$eloObjXml = $eloObj->generateXml();
		
		$addElo = "http://localhost:8080/roolo-standalone-server/UpdateELO?eloXML=".$eloObjXml;
		$msg = file_get_contents($addElo);
		
		$action = 'update';
		echo $msg;
	} elseif ($_REQUEST['action'] = 'load'){
		$action = 'update';
	}
}

?>


<html>


<!--<head>-->
<!---->
<!-- Load TinyMCE -->
<script type="text/javascript" src="/library/jquery-1.3.2.min.js"></script>
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
		Issue Title <br/>
		<input type="text" id="eloName" name="eloName" value="<?= $_REQUEST['eloName'] ?>"/>
		<br />
		<br />
		Issue Description
		<div>
			<div>
				<textarea id="eloContent" name="eloContent" rows="15" cols="80" style="width: 80%" class="tinymce">
					<?= htmlentities($_REQUEST['eloContent']) ?>
				</textarea>
			</div>
	
			<!-- Some integration calls -->
		
	
			<br />
			<input type="submit" name="save" value="Submit" />
			<input type="reset" name="reset" value="Reset" />
		</div>
		<input type="hidden" id="action" name="action" value="<?= $action?>" />
		<input type="hidden" id="eloId" name="eloId" value="<?=$_REQUEST['eloId'] ?>" />
		<input type="hidden" id="eloVersion" name="eloVersion" value="<?=$_REQUEST['eloVerion'] ?>" />
	</form>
</body>

</html>
