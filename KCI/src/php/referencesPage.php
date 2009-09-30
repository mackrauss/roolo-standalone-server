<?php
require_once 'ReferenceCategories.php';
require_once '../php/RooloClient.php';

require_once './header.php';
?>
		
		<script type='text/javascript' src='../../library/js/jquery-1.3.2.min.js'></script>
		<script type='text/javascript' src='../../library/js/jquery-ui-1.7.2.custom.min.js'></script>
		<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
		<title>References Page</title>
		<style>
			#centerArea { 
	 	 		width:100%;
//				overflow:auto; 
//	 	 		margin-left: auto;
//				margin-right: auto;
			}
		
	 	 	#categoriesArea {  
	 	 		width:80%;	 		
				padding:0% 10px 10px 10px; 
				overflow:auto; 
				font-size:70%; 
				font-family:arial;
				text-align:center; 
	 	 		list-style-type:none;
	 	 		cursor:pointer;
	 	 		margin-left: auto;
				margin-right: auto;
				font-size: 20;
			}
	 	 	li {
	 	 		float:left; 
	 	 		margin:0 10px;
	 	 	}
	 	 	li a {
	 	 		 margin:10px 10px 10px 0px; 
	 	 		 padding-top: 5px;
	 	 		 padding-bottom: 5px;
	 	 	}
	 	 	#buttonArea {
	 	 		width:90%;
	 	 		margin-left: auto;
				margin-right: auto;	
				padding:10px 0px 40px 10px; 
	 	 	}
	 	 	#referencesArea {
	 	 		width:40%;
				overflow:auto; 
				font-family:arial;
				text-align:left; 
	 	 		list-style-type:none;
	 	 		cursor:pointer;
	 	 			
				padding:0px 10px 10px 10px; 
	 	 	}
	 	 	#newReference {
	 	 		float:right;
	 	 	}
	    </style>
		
		<script type='text/javascript'>

			function hideShowAll(){
				$('#showAll').hide();
				return false;
			}
			//This function is executed when the click on one of reference title 	
			function goRefPage(id) {
				alert ("id =" + id);
				//$.get('http://localhost/KCI/src/php/search.php', {id: id },
				//	function(addedCitationURI){
				//	}
				// );
			}

			$(document).ready(function(){

				hideShowAll();
				$('a').click(function () { 
	        		$('.referenceTitle').hide();
	        		$('.' + this.id).fadeIn(250);
	    			$('#showAll').fadeIn(300);
	    			//$(this).css('color', '#000000');
					return false;
	    	    });
	    	    
				$('#showAll').click(function(){
					$('.referenceTitle').fadeIn(250);
					hideShowAll();
					return false;
				});
			})
		</script>
	</head>
	<body>
	<div id='centerArea'>
		<div id='categoriesArea'>
			<div style='float: right'>
				<a href='' id='showAll' style='padding-top:30px' > <font size="3"> Show all Categories </font></a>
			</div><br/><br/>
			<div id='categories'>
				<?php                                
					$referenceCategories = new ReferenceCategories();
					$refCatsList = $referenceCategories->getReferenceCategories(); 
					$result = "<ui>";
					for ($i = 0; $i < sizeof($refCatsList); $i++){
						$result .= "<li>"."<a id='".$refCatsList[$i]."' href='' onClick=''>".$refCatsList[$i]."</a></li>";		
					}
					$result .= "</ui>";
					echo $result;
				?>
			</div>
		</div>
	
		<div id='buttonArea'>
			<input id='newReference' type='button'  onClick = 'goRefPage()' value='Create a new Reference'>
		</div>
		<div id='referencesArea'>
			<?php 
				$searchStr = "type:Reference"; 
				$rooloClient = new RooloClient();
				$references = $rooloClient->search($searchStr, 'elo');

				//Makes html references title to send back to client
				$oddColor = '#FBFBF7';
				$evenColor = '#FBFBF7';
				//$addImagePath = 'http://localhost/KCI/resources/images/add.png';
				$result = "";
				foreach($references as $reference){
					$refID = $reference->get_id();
					$refCategory = $reference->get_category();
					$result .= "<div id='".$refID."' style='border:1px solid #FBFBF7';'class='".$refCategory." referenceTitle'>";
					$result .= "<p onClick='goRefPage(\"".$refID."\")'>".$reference->get_title()."</p>";
					$result .= "</div>";
					//$result .= "<img id='".$uriID."' src='".$addImagePath."' 
					//			 onClick='addCitation(\"".$uriID."\")' class='add_button'/>";
					list($oddColor, $evenColor) = array($evenColor, $oddColor);
				}
				echo $result;
			?>
		</div>
	</div>	
	
	
<?php 

require_once './footer.php';

?>	