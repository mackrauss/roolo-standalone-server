<?php
include_once '../RooloClient.php';
include_once '../dataModels/TeacherProgress.php';
include_once '../dataModels/RunConfig.php';
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

//echo $savedTpXml;

/*
 * Create RunConfig
 */
$rc = new RunConfig();
$rc->runid = 'v1-c1';
$rc->runchoicelimit = "A, B, C, D";
$savedRcXml = $roolo->addElo($rc);

echo $savedRcXml;


$rc = new RunConfig();
$rc->runid = 'v2-c1';
$rc->runchoicelimit = "A, B, C, D, E, F";
$savedRcXml = $roolo->addElo($rc);

echo $savedRcXml;

?>

<script type='text/javascript'>
	window.location.href = '/src/php/pages';
</script>