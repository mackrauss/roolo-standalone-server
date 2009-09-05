<?php 
require_once 'RooloClient.php';
require_once 'dataModels/Reference.php';
require_once 'dataModels/Tag.php';

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

				// This even should always precede the 
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
	    			$('.' + this.id).slideDown('fast');
	    			$('#allTags').fadeIn(300);
	    			$('li a').css('color', '#848484');
	    			$(this).css('color', '#74DF00');
	    	        return false; 
	    	    });

				$('#allTags').click(function(){
					$('.tag').slideDown('fast');
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
		$tags = $rooloClient->search('TYPE:tag', 'elo');
		
//		foreach ($tags as $tag){
//			if (array_key_exists($tag->get_title(), $tagFrequencyMap)){
//				$tagFrequencyMap[$tag->get_title()] += 1;				
//			}
//		}
//		
//		print_r($tagFrequencyMap);
//		die();
		
		foreach ($tags as $tag){
			echo $tag->get_title() . "<br/>";
			echo $tag->get_author() . "<br/><br/>";
			
		}
		die();
	
	?>

<ul id="tagList">

	
	<li>
		<a id="Java" href="" onClick="" style="font-size: 1.9em;">Java</a>
	</li>
	<li>
		<a id="javascript" href="" style="font-size: 1.9em;">javascript</a>
	</li>
	<li>
		<a id="Jquery" href="" style="font-size: 1.8em;">Jquery</a>
	</li>
	<li>
		<a id="PHP" href="" style="font-size: 1.3em;">PHP</a>
	</li>
	<li>
		<a id="HTML" href="" style="font-size: 1.7em;">HTML</a>
	</li>
	<li>
		<a id="XML" href="" style="font-size: 1.4em;">XML</a>
	</li>
	<li>
		<a id="Pascal" href="" onClick="" style="font-size: 1.6em;">Pascal</a>
	</li>
	<li>
		<a id="PL/1" href="" onClick="" style="font-size: 1.5em;">PL/1</a>
	</li>
	<li>
		<a id="FORTRAN" href="" style="font-size: 1.5em;">FORTRAN</a>
	</li>
	<li>
		<a id="COBOL" href="" onClick="" style="font-size: 1.9em;">COBOL</a>
	</li>
	<li>
		<a id="flash" href="" style="font-size: 1.9em;">flash</a>
	</li>
</ul>

<div id="newProposalDiv">
	<input id="newProposal" type="button" value="Create a new proposal" onClick="parent.location='http://localhost/src/php/issuePage.php'">
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
	<div id='1' class='Java tag' onClick="parent.location='http://localhost/src/php/issuePage?eloId=1'">
		<div id='t1' class='title'>Java</div>
		<div id='a1' class='author'>Java Author</div>
		<div id='d1' class='date'>Aug. 12, 2009 @ 14:25</div>
	</div>
	<div id='2' class='javascript tag'>
		<div id='t2' class='title'>javascript</div>
		<div id='a2' class='author'>javascript Author</div>
		<div id='d2' class='date'>Aug. 10, 2009 @ 14:00</div>
	</div>
	<div id='3' class='Jquery tag'>
		<div id='t3' class='title'>Jquery</div>
		<div id='a3' class='author'>Jquery Author</div>
		<div id='d3' class='date'>Feb. 01, 2009 @ 14:00</div>
	</div>
	<div id='4' class='PHP tag'>
		<div id='t4' class='title'>PHP</div>
		<div id='a4' class='author'>PHP Author</div>
		<div id='d4' class='date'>Jan. 11, 2008 @ 14:00</div>
	</div>
	<div id='5' class='HTML tag'>
		<div id='t5' class='title'>HTML</div>
		<div id='a5' class='author'>HTML Author</div>
		<div id='d5' class='date'>Jan. 10, 2008 @ 14:00</div>
	</div>
	<div id='6' class='XML tag'>
		<div id='t6' class='title'>XML</div>
		<div id='a6' class='author'>XML Author</div>
		<div id='d6' class='date'>Sep. 10, 2008 @ 14:00</div>
	</div>
	<div id='7' class='Pascal tag'>
		<div id='t7' class='title'>Pascal</div>
		<div id='a7' class='author'>Pascal Author</div>
		<div id='d7' class='date'>Sep. 10, 2008 @ 14:00</div>
	</div>
	<div id='8' class='PL/1 tag'>
		<div id='t8' class='title'>PL/1</div>
		<div id='a8' class='author'>PL/1 Author</div>
		<div id='d8' class='date'>Sep. 10, 2008 @ 14:00</div>
	</div>
	<div id='9' class='FORTRAN tag'>
		<div id='t9' class='title'>FORTRAN</div>
		<div id='a9' class='author'>FORTRAN Author</div>
		<div id='d9' class='date'>Sep. 10, 2008 @ 14:00</div>
	</div>
	<div id='10' class='COBOL flash tag'>
		<div id='t10' class='title'>COBOL flash</div>
		<div id='a10' class='author'>COBOL flash Author</div>
		<div id='d10' class='date'>Sep. 10, 2008 @ 14:00</div>
	</div>
	<div id='11' class='COBOL tag'>
		<div id='t11' class='title'>COBOL</div>
		<div id='a11' class='author'>COBOL Author</div>
		<div id='d11' class='date'>Sep. 10, 2008 @ 14:00</div>
	</div>
	<div id='12' class='flash tag'>
		<div id='t12' class='title'>flash</div>
		<div id='a12' class='author'>flash Author</div>
		<div id='d12' class='date'>Sep. 10, 2008 @ 14:00</div>
	</div>
	
</div>

	</body>
</html>

