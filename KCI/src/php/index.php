<?php 


require_once './header.php';

?>
 
<style type='text/css'> 


	li {
		margin-bottom: 25px;
	}
	
	li input {
		margin-top: 10px;
	}

</style>

<h3 style='margin-bottom: 50px'> Welcome to the climate change unit website. This where you can add references, review topics and add issues. </h3>

<ol>
	<li> If you haven't already, take a look at the Unit Outline for more information about this unit <br/>
	     <input type="button" class="SmallButton" value="UNIT OUTLINE page >" onClick="document.location.href=''"/>
	</li>
	
	<li> If you haven't already, add some references to your categories here <br/>
	     <input type="button" class="SmallButton" value="REFERENCES page >" onClick="document.location.href='/src/php/referencesPage.php'"/>
	</li>
	
	<li> Work on your issues here. Also review and contribute to other group's issues here. <br/>
	     <input type="button" class="SmallButton" value="ARTICLES page >" onClick="document.location.href='/src/php/articles.php'"/>
	</li>
</ol>


 