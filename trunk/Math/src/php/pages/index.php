<?php 
require_once './header.php';

if (isset($_REQUEST['username'])){
	$_SESSION['username'] = $_REQUEST['username'];	
}

?>
 
<style type='text/css'> 


	li {
		margin-bottom: 25px;
	}
	
	li input {
		margin-top: 10px;
	}

</style>


<h3 style='margin-bottom: 50px'> Welcome to the Math project</h3>

<ol>
	<li> Tag questions <br/>
	     <input type="button" class="SmallButton" value="Tag Questions > " onClick="document.location.href='/src/php/pages/tag_question.php';"/>
	</li>
</ol>


 