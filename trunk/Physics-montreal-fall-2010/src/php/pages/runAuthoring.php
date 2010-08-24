<?php
require_once './header.php';
require_once '../RooloClient.php';
require_once '../dataModels/Problem.php';
require_once '../dataModels/Principle.php';
require_once '../dataModels/SuperSummary.php';
require_once '../Application.php';
require_once '../dataModels/Solution.php';

error_reporting(E_ALL | E_STRICT);

if (!$_SESSION['loggedIn']){
	header("Location:/src/php/pages/");
}

$_SESSION['msg'] = "";
$username = $_SESSION['username'];
?>

<script type="text/javascript">

</script>

<h2>Current Runs</h2>
<input type='button' onclick="window.location.href='/src/php/pages/runCreate.php';" value='Create New Run' />

<?php 
require_once './footer.php';
?>
