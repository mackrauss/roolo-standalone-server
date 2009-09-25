<?php 
require_once 'RooloClient.php';
require_once 'dataModels/Tag.php';
require_once 'dataModels/Elo.php';
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<script type="text/javascript" src="/library/js/jquery-1.3.2.min.js"></script>
 		<link rel="stylesheet" type="text/css" href="/src/css/proposal.css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>KCI</title>
	 	<style>
	 	 	a { color:#01DF74; margin:10px 10px 10px 0px; cursor:pointer; }
	    </style>
		
		<script type='text/javascript'>

			//Change the background color of the div
			// which we have hovered over 
			function changeRowColor(){

			}

			// Hide the "show all" link when it is clicked
			function hideShowAll(){
				$('#allTags').hide();
				return false;
			}
		
			$(document).ready(function(){
				$('#tagList').appendTo("#tagCloud");

				$('#allTags').hide();

				$('div.tag').mouseover(function(){
					$(this).css('background-color', '#E6E6E6');
				}).mouseout(function(){
					$(this).css('background-color', 'white');
				});

				// This event should always precede the 
				// next $('#allTags').click event. This is because
				// the "show all" link should hide when it is clicked

				$("a").hover(
					function () {
			      		$(this).addClass("hilite");
			    	}, function () {
			      		$(this).removeClass("hilite");
			    });
				
				$("a").click(function () { 
	        		$('.tag').hide();
	    			$('.' + this.id).slideDown('800');
	    			$('#allTags').fadeIn(300);
	    			$('li a').css('color', '#848484');
	    			$(this).css('color', '#74DF00');
	    	        return false; 
	    	    });

				$('#allTags').click(function(){
					$('.tag').slideDown('800');
					$('li a').css('color', '#848484');
					hideShowAll();
					return false;
				});
			    
		    });
	
		</script>
	</head>
		
<body>

<div id="tagCloud" class='center'>
	<div style='float: right'>
		<a href='' id='allTags' style='text-decoration:none'> <font size="4"> Show all Tags </font></a>
	</div>
	<br/>
	<font color="#1C1C1C" size="5">Tag Cloud</font>
	<br/>
	<br/>
	
</div>



<?php 
		$rooloClient = new RooloClient();
		$tagFrequencyMap = array();
		$tags = $rooloClient->search('type:Tag AND ownertype:Citation', 'metadata');
		
		//TODO the 'uri' parameter of search doesn't seem to work. That option should return
		// an array of URIs
		$citations = $rooloClient->search('type:Citation', 'metadata');
		
		$citationUris = array();
		
		
		// Iterate through all the tags and do the following two tasks:
		// 1. extract the frequency of each tag to decide it's font size
		// 2. create a mapping between each citation and all its corresponding tags
		foreach ($tags as $tag){
			if (array_key_exists($tag->get_title(), $tagFrequencyMap)){
				$tagFrequencyMap[$tag->get_title()] += 1;				
			}else {
				$tagFrequencyMap[$tag->get_title()] = 1;
			}
			
			
			if (array_key_exists($tag->get_ownerUri(), $citationUris)){
				$citationUris[$tag->get_ownerUri()][] = str_replace(' ', '', $tag->get_title());
			}else{
				$citationUris[$tag->get_ownerUri()] = array();
				$citationUris[$tag->get_ownerUri()][] = str_replace(' ', '', $tag->get_title());
			}
		}
		
		
		echo "<ul id='tagList'>";
		
		foreach ($tagFrequencyMap as $tag => $tagFrequency){
			
			$fontSize = 1 + ($tagFrequency / 10);	
		
			echo "<li>";
			echo "<a id='" . str_replace(' ', '', $tag) . "' href='' style='font-size: " . $fontSize . "em'>". $tag ."</a>";
			echo "</li>";
			
		}
		echo "</ul>";
	?>


<div id="newProposalDiv">
	<input id="newProposal" type="button" value="Create a new citation" onClick="parent.location='http://localhost/src/php/citationPage.php'">
</div>
<br/>
<br/>
<br/>
	
<div id='tagDivs' class='center'>

	<div id='proposalTitle'>
		Title
	</div>
	
	<div id='proposalAuthor'>
		Author
	</div>
	
	<div id='proposalDate'>
		Date Updated
	</div>

	<br/>
	<br/>
	
	<?php 
		foreach ($citationUris as $citationUri => $tagArray){
			
			$citation = $rooloClient->retrieveElo($citationUri);
			
			$citationId = $citation->get_id();
			
			echo "<div class='tag " . implode(' ', $tagArray) . "' onClick=\"parent.location='http://localhost/src/php/citationPage.php?id=" . $citationId . "'\">" .
				 "<div class='title'>" . $citation->get_title() . "</div>" .
				 "</div>";
		}
	?>
	

	
</div>

	</body>
</html>

