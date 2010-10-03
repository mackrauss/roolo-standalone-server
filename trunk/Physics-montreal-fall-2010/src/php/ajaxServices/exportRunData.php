<?php 
@session_start();

require_once '../RooloClient.php';
require_once '../CommonFunctions.php';
require_once '../dataModels/RunConfig.php';
require_once '../class.Email.php';
require_once '../pclzip.lib.php';


/*
 * Get Params
 */
$runId = $_REQUEST['runId'];
//$email = $_REQUEST['email'];

/*
 * Setup variables
 */
$roolo = new RooloClient();
$appRoot = dirname(__FILE__).'/../../..';
$graphmlServicesPath = "/src/php/graphmlServices";

/*
 * Find all ELOs that are attached to this run
 */
$runElos = $roolo->search('runid:'.$runId, 'metadata', 'all');

/*
 * Extract elosXml string and an array version of each ELO
 */
$elosXml = '';
$runElosArray = array();
$runElosArrayKeys = array('datecreated' => 1, 'datelastmodified' => 1, 'type' => 1, 'uri' => 1, 'version' => 1, 'author' => 1);
foreach ($runElos as $curRunElo){
	$curRunEloArray = $curRunElo->toArray();
	$elosXml .= $curRunElo->generateXml();
	
	$runElosArray[] = $curRunEloArray;
	$runElosArrayKeys = $runElosArrayKeys + $curRunEloArray;
}
$runElosArrayKeys = array_keys($runElosArrayKeys);

/*
 * Print CSV Headers
 */
$elosCsv = '';
$firstColumn = true;
foreach ($runElosArrayKeys as $curColumnKey){
	$elosCsv .= $firstColumn ? '': ',';
	$elosCsv .= '"'.str_replace('"', '\"', $curColumnKey).'"';
	
	$firstColumn = false;	
}
$elosCsv .= "\n";

/*
 * Print CSV rows (1 elo per row)
 */
foreach ($runElosArray as $curEloArray){
	$firstColumn = true;
	foreach ($runElosArrayKeys as $curColumnKey){
		$curColumnValue = $curEloArray[$curColumnKey];

		if ($curColumnKey == 'datecreated' ||
			$curColumnKey == 'datelastmodified'){

			$curColumnValue = date("F j, Y, g:i a",$curColumnValue/1000);
		}
		
		$elosCsv .= $firstColumn ? '': ',';
		$elosCsv .= '"'.str_replace('"', '\"', $curColumnValue).'"';
		
		$firstColumn = false;	
	}
	
	$elosCsv .= "\n";
}

$elosCsv = trim($elosCsv);
$elosXml = trim($elosXml);

/*
 * Create export data directory 
 */
$dirPath = $appRoot.'/exportedData';
if (!is_dir($dirPath)){
	if(!mkdir($dirPath)){
		echo "couldn't create directory";
		die();
	}
}

/*
 * Create CSV file
 */
$csvPath = $dirPath.'/elos.csv';
$fh = fopen($csvPath, 'w');
fwrite($fh, $elosCsv);
fclose($fh);

/*
 * Create XML file
 */
$xmlPath = $dirPath.'/elos.xml';
$fh = fopen($xmlPath, 'w');
fwrite($fh, $elosXml);
fclose($fh);

/*
 * Zip up all the files along with the run's problem directory 
 */
$zipName = "$runId.zip";
$zipPath = $dirPath."/".$zipName;
$zipfile = new PclZip($zipPath);
$v_list = $zipfile->create(array(
	$csvPath, 
	$xmlPath, 
	$appRoot.'/problems/'.$runId
));
if ($v_list == 0) {
	die ("Error: " . $zipfile->errorInfo(true));
}

echo "/exportedData/$zipName";

//header("Content-type: application/octet-stream");
//header("Content-disposition: attachment; filename=$zipName");
//readfile($zipPath);

/*
 * Setup and send email, attaching the CSV and XML files to it
 */
//$Recipiant = $email;
//$Subject = "Exported Dawson Run Data for ".$runId;
//$Sender = 'data@dawsoncollege.qc.ca';
//$Cc = '';
//$Bcc = '';
//
//$msg = new Email($Recipiant, $Sender, $Subject); 
//$msg->Cc = $Cc;
//$msg->Bcc = $Bcc;
//$msg->TextOnly = false;
//$msg->Content = "Please find the data files attached";
//$msg->Attach($csvPath, 'text/plain');
//$msg->Attach($xmlPath, 'text/xml');
//
//$SendSuccess = $msg->Send();
//echo $SendSuccess;
//
//echo "SUCCESS";