<?php 
session_start();

include_once 'header.php';
?>
<h2>Please choose...</h2>
<ul>
	<li style='margin-bottom: 5px;'><a href='/src/php/pages/upload_solution.php'> Answer Questions </a> <br/></li>
	<li><a href='/src/php/pages/voteOnOtherTags.php'> Vote on Other Groups' work </a> <br/></li>
</ul>

<?php 
include_once 'footer.php';
?>