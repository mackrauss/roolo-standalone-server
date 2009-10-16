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
?>

<h2>Articles</h2>
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
	<input type='button' class='SmallButton' onclick="document.location.href='/src/php/articlePage.php?action=create'" value='Create an Article'/>
</div>

<?php
include_once('footer.php')
?>