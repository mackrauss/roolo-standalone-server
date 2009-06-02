<?php


function fetch($vis_name){
	$file = fopen ("http://142.150.102.195:8080/buffer-server", "r");
	
	while (!feof ($file)) {
	    $line = fgets ($file, 1024);
	    echo $line;
	}
	fclose($file);	
}

?>