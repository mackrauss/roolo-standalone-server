<?php
session_start();
require_once './header.php';
require_once 'ReferenceCategories.php';
require_once '../php/RooloClient.php';

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
	 	 		//margin-left: auto;
				//margin-right: auto;
				margin-right: 20px;	
				padding:10px 0px 40px 10px; 
	 	 	}
	 	 	#referencesArea {
	 	 		width:80%;
				overflow:auto; 
				font-family:arial;
				text-align:left; 
	 	 		list-style-type:none;
	 	 		cursor:pointer;

				border-top: 3px solid #8eff61;
	 	 		
	 	 		margin-left: 10%;
				padding:0px 10px 10px 10px; 
	 	 	}
	 	 	#newReference {
	 	 		float:right;
	 	 	}
	 	 	
	 	 	.BigButton{
	 	 		font-size: 16px;
	 	 		width: 200px;
	 	 		height: 40px;
	 	 		margin-bottom: 2%;
	 	 	}
	 	 	
	    </style>
		
		<script type='text/javascript'>

			function hideShowAll(){
				$('#showAll').hide();
				return false;
			}
			//This function is executed when the click on one of reference title 	
			function goToRefPage(id) {
				if (id == ""){
					location = "http://localhost/src/php/referencePage.php";
				}else{
					location = "http://localhost/src/php/referencePage.php?id=" + id;
				}
			}

			function changeBackgroundColor(selectedRow){
				$(selectedRow).hover(function (){
					$(selectedRow).css('background-color', '#e6ffe7');
				},function (){
					$(selectedRow).css('background-color', '#f9fffe');
				});
			}

			$(document).ready(function(){

				hideShowAll();
				$('a').click(function () { 
	        		$('.referenceTitle').hide();
	        		$('.' + this.id).fadeIn(250);
	    			$('#showAll').fadeIn(300);
					return false;
	    	    });
	    	    
				$('#showAll').click(function(){
					$('.referenceTitle').fadeIn(250);
					hideShowAll();
					return false;
				});

			});
		</script>
	</head>
	<body>
	<div id='centerArea'>
		<div id='categoriesArea'>
			<font size="4px" style="float: left"> Reference Categories </font>
			<div style='float: right'>
				<a href='' id='showAll' style='padding-top:30px' > <font size="3"> Show all References </font></a>
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
			<input id='newReference' type='button' class='BigButton' onClick ='goToRefPage("")' value='Create a new Reference'>
		</div>
		<div id='referencesArea'>
			<?php 
				$searchStr = "type:Reference"; 
				$rooloClient = new RooloClient();
				$references = $rooloClient->search($searchStr, 'elo', 'latest');
				$references = array_reverse($references);

				//Makes html references title to send back to client
				$oddColor = '#FBFBF7';
				$evenColor = '#a8ff7f';
				
			    echo "<div style='margin-top: 3%; margin-bottom: 8%'> <span style='width: 60%; float:left; font-size: large'> References </span> <span style='width: 30%; float:right; font-size: large'>Last Modified On </span> " . 
			    			" </div>\n";
				
				$result = "";
				foreach($references as $reference){
					$refID = $reference->get_id();
					$refCategory = $reference->get_category();
					$dateLastModified = date('l g:i a - F jS' , $reference->get_datelastmodified()/1000); 
					
					$result .= "<div id='".$refID."' class='".$refCategory." referenceTitle' style='height:7px; padding:2% 0% 2% 2%;' onMouseOver='changeBackgroundColor(this)' onClick='goToRefPage(\"".$refID."\")'>";
					$result .= "<div style='width: 49%; float: left'>";
					$result .= $reference->get_title() . "</div>";
					$result .= "<div style='width: 30%; float: right'> " . $dateLastModified . "  </div>";
					$result .= "</div>\n";
				}
				echo $result;
			?>
		</div>
	</div>	
	
	
<?php 

require_once './footer.php';

?>	