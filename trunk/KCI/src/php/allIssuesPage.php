<?php
$retrieveAll = "http://localhost:8080/roolo-standalone-server/RetrieveAll";
$allIssues = file_get_contents($retrieveAll);

?>

<h2> All issues </h2>


<ul>

	<?php 
		include_once '../../domLib/simple_html_dom.php';
		include_once 'dataModels/Elo.php';
		$dom = str_get_dom($allIssues);
		
		$allElos = $dom->find('elo');
		
		$i=0;
		foreach($allElos as $elo){
			$eloObj = new Elo($elo->outertext);

			$issueContent = $eloObj->getContent();
			$issueContentDom = str_get_dom($issueContent);
			$issueContentDom = $issueContentDom->find('data');
			$issueContentStr = $issueContentDom[0]->innertext;
			
//			$issueContentStr
			$issueName = $eloObj->getMetadata('title');
			$issueUri = $eloObj->getMetadata('uri');
			$issueVersion = $eloObj->getMetadata('version');
			
			$issueLink = 'issuePage.php?eloId=' . urlencode($issueUri) . '&eloVersion=' . urlencode($issueVersion) . '&eloName='. urlencode($issueName) . '&eloContent=' . urlencode($issueContentStr) . '&action=load';
			echo "<li> Issue: <a href='$issueLink'>" . $issueName . "</a></li>";
			$i++;
		}
	
	?>

</ul>