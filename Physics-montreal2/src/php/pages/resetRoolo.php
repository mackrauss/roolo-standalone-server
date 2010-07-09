<?php
include_once '../RooloClient.php';

$roolo = new RooloClient();

$roolo->deleteAll();

`rm -rf ../../../Problems/*`;

include_once '../script/script_moveRenameCreateProblemsObjects.php';

?>

<!--<script type='text/javascript'>-->
<!--	window.location.href = '/src/php/pages';-->
<!--</script>-->