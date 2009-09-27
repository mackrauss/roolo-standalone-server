<?php 
require_once 'header.php';

require_once 'RooloClient.php';
require_once 'dataModels/Elo.php';
require_once 'dataModels/Reference.php';
require_once 'ReferenceCategories.php';
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<script type="text/javascript" src="/library/js/jquery-1.3.2.min.js"></script>
 		<link rel="stylesheet" type="text/css" href="/src/css/proposal.css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>KCI</title>
	 	
		
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


<?php 
		$rooloClient = new RooloClient();
	
?>



<div id='categoryDiv'>

	<table id='categoryTable' style='margin-left:auto; margin-right:auto'>
	
	<?php 
	
		$referenceCategory = new RefereneceCategories();
		$referenceCategories = $referenceCategory->getReferenceCategories();
		
		for ($i=0; $i < sizeof($referenceCategories); $i+=4){
	?>
	
			<tr> 
				<td> <a href='' ><?= @$referenceCategories[$i]?>  </a> </td>
				<td> <a href='' ><?= @$referenceCategories[$i+1]?> </a> </td>
				<td> <a href='' ><?= @$referenceCategories[$i+2]?> </a> </td>
				<td> <a href='' ><?= @$referenceCategories[$i+3]?> </a> </td>
			</tr>	
	
	<?php		
		}
	?>

	</table>

</div>


<div id="newProposalDiv">
	<input id="newProposal" type="button" value="Create a new citation" onClick="parent.location='http://localhost/src/php/citationPage.php'">
</div>
<br/>
<br/>
<br/>
	
<div id='tagDivs' class='center'>

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

<?php
	require_once 'footer.php';
?>
