<?php
include_once 'header.php';
?>

<?php 
require_once 'RooloClient.php';
require_once 'dataModels/Tag.php';
require_once 'dataModels/Elo.php';

/*
 * Grab all the articles in the system
 */
$roolo = new RooloClient();
$articles = $roolo->search('type:Article', 'metadata', 'latest');
$articles = sortArticles($articles);

//$s = array('3' => 'three', '1' => 'one', '2' => 'two');
//ksort($s);
//foreach($s as $curS){
//	echo $curS;
//}

function sortArticles($articles){
	$dates = array();
	$midArticles = array();
	foreach($articles as $article){
		$midArticles[$article->get_datelastmodified()] = $article;
	}
	
	krsort($midArticles);
	$sortedArticles = array();
	foreach($midArticles as $article){
		$sortedArticles[] = $article;
	}
	
	return $sortedArticles;
}
?>

<h2>Climate Change Issues</h2>
<div> 
	<ul>
	<?php 
	foreach($articles as $article){
	?>
		<li>
			<a href='/src/php/articlePage.php?action=load&articleUri=<?=$article->get_uri()?>'><?= $article->get_title() ?></a>
			<span class='small_info'> Last Edited <em><?= date('F jS',$article->get_datelastmodified()/1000)?></em> by <em><?= $article->get_author()?></em></span>
		</li>
	<?php
	}
	?>
	</ul> 
</div>
<div style='text-align: right'>
	<img src='/src/images/add.png' />
	<input type='button' class='SmallButton' onclick="document.location.href='/src/php/articlePage.php?action=create'" value='Create an Issue'/>
</div>

<?php
include_once('footer.php')
?>