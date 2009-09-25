<?php
require_once 'dataModels/Reference.php';
require_once 'dataModels/Tag.php';
require_once 'dataModels/Citation.php';
require_once 'RooloClient.php';

error_reporting(E_STRICT);

if ($_REQUEST['isAjax']){

	if ($_REQUEST['function'] == 'createReference'){
		//echo "in the createReference";
		$modifiedUri = trim($_GET['id']) ;
		//echo "modified Uri = ".$modifiedUri;
		createReference(encode($eloUri));
	}
	if ($_REQUEST['function'] == 'search'){
		searchCitationByTag();
	}

}else {
?>
<html>
	<head>
	<script type='text/javascript' src='../../library/js/jquery-1.3.2.min.js'></script>
	<script type='text/javascript' src='../../library/js/jquery-ui-1.7.2.custom.min.js'></script>
	<link rel='stylesheet' type='text/css' href='../css/article.css'>
	<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
	<title>Search</title>
	<script type='text/javascript'>

		function addCitation(currentUri) {
			var uriModified = currentUri;
			var selectedID = "#" + uriModified;
			htmlStr = $(selectedID).html();
			$(selectedID).hide(500);
			$('#searchList').children(selectedID).hide();
			var div = $('<div>').attr("id",uriModified);
			$(htmlStr).appendTo(div);
			$('<img>').attr({onClick:"removeCitation('" + uriModified +"')", src:"/resources/icon/cross.png", id:uriModified}).addClass("delete_button").appendTo(div);
			$('#citation_area').append(div);

//			//Request the search.php page and get the search resault
			//$.get('http://localhost/KCI/src/php/search.php', { action:'addCitation', eloUri:uri, eloTitle:title },
			$.get('http://localhost/src/php/article.php', { isAjax:'true', function:'createReference', id: uriModified },
				function(htmlResult){
					alert('Data Loaded after click on add image: ' + htmlResult);
				}
			 );
		}

		function removeCitation(curentUri){
			//alert("curentUri =" + curentUri);
			$('#citation_area').children('#' + curentUri).remove();
		}

		$(document).ready(function() {
			$('#textArea1').attr('disabled', true);
			$('#textArea2').attr('disabled', true);
			$('#textArea3').attr('disabled', true);
			$('#section1_cancel_btn').attr('disabled', true);
			$('#section1_update_btn').attr('disabled', true);
			$('#section2_cancel_btn').attr('disabled', true);
			$('#section2_update_btn').attr('disabled', true);
			$('#section3_cancel_btn').attr('disabled', true);
			$('#section3_update_btn').attr('disabled', true);

			$('.edit_btn').click(function(){
				if(this.id == 'section1_edit_btn' ){
					$('#textArea1').attr('disabled', false);
					$('#section1_cancel_btn').attr('disabled', false);
					$('#section1_update_btn').attr('disabled', false);
					$('#textArea2').attr('disabled', true);
					$('#section2_cancel_btn').attr('disabled', true);
					$('#section2_update_btn').attr('disabled', true);
					$('#textArea3').attr('disabled', true);
					$('#section2_cancel_btn').attr('disabled', true);
					$('#section2_update_btn').attr('disabled', true);
				}
				if(this.id == 'section2_edit_btn' ){
					$('#textArea2').attr('disabled', false);
					$('#section2_cancel_btn').attr('disabled', false);
					$('#section2_update_btn').attr('disabled', false);
					$('#textArea1').attr('disabled', true);
					$('#section1_cancel_btn').attr('disabled', true);
					$('#section1_update_btn').attr('disabled', true);
					$('#textArea3').attr('disabled', true);
					$('#section3_cancel_btn').attr('disabled', true);
					$('#section3_update_btn').attr('disabled', true);
				}
				if(this.id == 'section3_edit_btn' ){
					$('#textArea3').attr('disabled', false);
					$('#section3_cancel_btn').attr('disabled', false);
					$('#section3_update_btn').attr('disabled', false);
					$('#textArea1').attr('disabled', true);
					$('#section1_cancel_btn').attr('disabled', true);
					$('#section1_update_btn').attr('disabled', true);
					$('#textArea2').attr('disabled', true);
					$('#section2_cancel_btn').attr('disabled', true);
					$('#section2_update_btn').attr('disabled', true);
				}
			});


			//click on title and hide and show the textaria and buttons
			$('.title').click(function() {
				var section ='';
				var titleText ='';
				if (this.id == 'title1'){
					section = '#section1';
					if ($(this).text() == 'The title of section one' )
						titleText = 'The title of section one (click to show!)';
					else
						titleText = 'The title of section one';
				}
				if (this.id == 'title2'){
					section = '#section2';
					if ($(this).text() == 'The title of section two' )
						titleText = 'The title of section two (click to show!)';
					else
						titleText = 'The title of section two';
				}
				if (this.id == 'title3'){
					section = '#section3';
					if ($(this).text() == 'The title of section three' )
						titleText = 'The title of section three (click to show!)';
					else
						titleText = 'The title of section three';
				}
				//run the effect
				$(section).toggle('blind',{},500);
				$(this).text(titleText);
				return false;
			});


			$('#search_button').click(function(){

				var textField = $('#searchText').val();
				//Request the search.php page and get the search resault
				$.get('http://localhost/src/php/article.php', { isAjax:'true', function:'search', searchItem: textField },
					function(htmlResult){
						//removeOldSearchResault();
						//alert('Data Loaded: ' + htmlResult);
						$('#searchList').html(htmlResult);
					}
				 );
			});

			return false;
		});
	</script>
	</head>
	<body>
		<div id='page_dvi'>
			<label class='title' id='title1'>The title of section one</label>
			<div class='section' id='section1'>
				<textarea class='section_text' rows='10' cols='80' id='textArea1'>
					
				</textarea>
				<div class='section_button_Div'>
					<input class='section_button' type='button' id='section1_cancel_btn' value='Cancel'>
					<input class='section_button' type='button' id='section1_update_btn' value='Update'>
					<input class='section_button edit_btn' type='button' id='section1_edit_btn' value='Edit'>
				</div>
			</div>
			<label class='title' id='title2'>The title of section two</label>
			<div class='section' id='section2'>
				<textarea class='section_text' rows='10' cols='80' id='textArea2'>
					
				</textarea>
				<div class='section_button_Div'>
					<input class='section_button' type='button' id='section2_cancel_btn' value='Cancel'>
					<input class='section_button' type='button' id='section2_update_btn' value='Update'>
					<input class='section_button edit_btn' type='button' id='section2_edit_btn' value='Edit'>
				</div>
			</div>
			<label class='title' id='title3'>The title of section three</label>
			<div class='section' id='section3'>
				<textarea class='section_text' rows='10' cols='80' id='textArea3'>
					
				</textarea>
				<div class='section_button_Div'>
					<input class='section_button' type='button' id='section3_cancel_btn' value='Cancel'>
					<input class='section_button' type='button' id='section3_update_btn' value='Update'>
					<input class='section_button edit_btn' type='button' id='section3_edit_btn' value='Edit'>
				</div>
			</div>

			<div>
				<label class='title'>this is the tag area</label>
				<textarea class='tag' rows='5' cols='80' id='tag_textArea'>
					
				</textarea>
			</div>
			<label class='title'>this is the citation area</label>
			<div id='citation_area'>
			</div>
			<div class='search'>
				<label class ='searchLabel' style='font-size:30'>Search</label>
				<input type='text' id='searchText'>
				<input type='button' class='search_button' id='search_button'  value='Search' >
			</div>
			<div id='searchList'></div>
		</div>
	</body>
</html>
<?php
} // the braket of else
?>
<?php

	function searchCitationByTag(){

		$searchItem = trim($_GET['searchItem']);
		$tagsTitles = explode(" ", $searchItem);

		// make the search string for retrieve tags ELO
		$searchStr = "TYPE:Tag AND (";
		for($i=0; $i < sizeof($tagsTitles); $i++){
			$searchStr .= "TITLE:".trim($tagsTitles[$i]);
			if ($i < sizeof($tagsTitles) - 1){
				$searchStr .= " OR ";
			}
		}
		$searchStr .= ")";
		
		$rooloClient = new RooloClient();
		$tags = $rooloClient->search($searchStr, 'elo');

		//Search for Citation ELO/s
		$citations = array();
		foreach ($tags as $tag){
//			$citationUri = $rooloClient->getUriDomain() . $tag->get_ownerUri() . '.Citation';
			$citationUri = $tag->get_ownerUri();
			$curCitation = $rooloClient->retrieveElo($citationUri);
			$citations[$citationUri] = $curCitation;
		}

		//Make html tags to send back to client
		$oddColor = '#F2F0F0';
		$evenColor = '#FFFFFF';
		$addImagePath = '/resources/icon/add.png';
		$result = "";
		foreach($citations as $citation){
			$uriID = decode($citation->get_uri());
			$result .= "<div id='".$uriID."' style='background-color:".$oddColor.";'class='searchResult'>";
			$result .= "<p> Author:".$citation->get_author()."_____Date Created:".$citation->get_dateCreated()."</p>";
			$result .= "<p> Title:".$citation->get_title()."</p>";
			$result .= "</div>";
			$result .= "<img id='".$uriID."' src='".$addImagePath."'
						 onClick='addCitation(\"".$uriID."\")' class='add_button'/>";
			list($oddColor, $evenColor) = array($evenColor, $oddColor);
		}
		echo $result;
	}

	function createReference($uri){

		$reference = new reference();

		$reference->set_uri('');
		$reference->set_version('');
		$reference->set_dateCreated('');
		$reference->set_dateModified('');
		$reference->set_type("reference");
		$reference->set_title("reference".$uri);
		$reference->set_author("author_Reference_1");

		$reference->set_uri1($_uri);
		$reference->set_uriType1("Citation");
		$reference->set_uri2($_uri2);
		$reference->set_uriType2("Article");
		$rooloClient = new RooloClient();
		echo $rooloClient->addElo($reference);
	}

	//modifies elo's uri for using it as html tag's id
	function decode($uri){
		return str_replace(".", "_", $uri);
	}

	//brings back modified elo's uri to original form
	function encode($modifiedUri){
		return str_replace(".", "_", $modifiedUri);
	}
?>
