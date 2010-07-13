<?php
include_once '../RooloClient.php';
include_once '../dataModels/TeacherProgress.php';

$roolo = new RooloClient();

$roolo->deleteAll();

`rm -rf ../../../Problems/*`;

/*
 * Create questions
 */
include_once '../script/script_moveRenameCreateProblemsObjects.php';

/*
 * Create TeacherProgress
 */
$tp = new TeacherProgress();
$tp->set_progress('');
$savedTpXml = $roolo->addElo($tp);

echo $savedTpXml;
?>

<script type='text/javascript'>
	window.location.href = '/src/php/pages';
</script>