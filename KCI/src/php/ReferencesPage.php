<?php
//require_once '../php/ReferenceCategories.php';
require_once './ReferenceCategories.php';
require_once '../php/RooloClient.php';
?>
<html>
    <head>
        <script type='text/javascript' src='../../library/js/jquery-1.3.2.min.js'></script>
        <script type='text/javascript' src='../../library/js/jquery-ui-1.7.2.custom.min.js'></script>
        <link rel='stylesheet' type='text/css' href='../css/search.css'>
        <meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1'>
        <title>References Page</title>
        <style>
            #centerArea { 
                  width:70%;
                padding:0px; 
                overflow:auto; 
                  margin-left: auto;
                margin-right: auto;
                //background:#F0F0F0;    
            }
        
              #categoriesArea {  
                  width:80%;             
                padding:10px 10px 10px 10px; 
                overflow:auto; 
                font-size:70%; 
                font-family:arial;
                text-align:center; 
                  list-style-type:none;
                  cursor:pointer;
                  margin-left: auto;
                margin-right: auto;
                //background:#FEF9D8;    
            }
              li {
                  float:left; 
                  margin:0 10px;
              }
              li a {
                   margin:10px 10px 10px 0px; 
              }
              #buttomArea {
                  width:80%;
                  margin-left: auto;
                margin-right: auto;    
                padding:10px 10px 10px 10px; 
              }
              #referencesArea {
                  width:80%;
                padding:0px; 
                overflow:auto; 
                font-family:arial;
                text-align:left; 
                  list-style-type:none;
                  cursor:pointer;
                  margin-left: auto;
                margin-right: auto;    
                padding:0px 10px 10px 10px; 
                //background:#FAEA83;
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
                //    function(addedCitationURI){
                //    }
                // );
            }

            $(document).ready(function(){

                hideShowAll();
                $('a').click(function () { 
                    $('.referenceTitle').hide();
                    $('.' + this.id).fadeIn(250);
                    $('#showAll').fadeIn(300);
                    //$('li a').css('color', '#898989');
                    $(this).css('color', '#000000');
                    return false;
                });
                
                $('#showAll').click(function(){
                    $('.referenceTitle').fadeIn(250);
                    $('li a').css('color', '#898989');
                    hideShowAll();
                    return false;
                });
                //return false;
            })
        </script>
    </head>
    <body>
    <div id='centerArea'>
        <div id='categoriesArea'>
            <div style='float: right'>
                <a href='' id='showAll' > <font size="3"> Show all Categories </font></a>
            </div><br/><br/>
            <div id='categories'>
                <?php                                               
                    $referenceCategories = new RefereneceCategories();
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
    
        <div id='buttomArea'>
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
                    //echo $refCategory;
                    //die();
                    $result .= "<div id='".$refID."' style='background-color:#FBFBF7';'class='".$refCategory." referenceTitle'>";
                    $result .= "<p onClick='goRefPage(\"".$refID."\")'> <b>Title:</b>".$reference->get_title()."</p>";
                    $result .= "</div>";
                    //$result .= "<img id='".$uriID."' src='".$addImagePath."' 
                    //             onClick='addCitation(\"".$uriID."\")' class='add_button'/>";
                    list($oddColor, $evenColor) = array($evenColor, $oddColor);
                }
                echo $result;
            ?>
        </div>
    </div>    
    </body>
</html>