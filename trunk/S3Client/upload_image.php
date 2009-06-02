<?php
//  print_r($_FILES['solution']);  
   $solutionFile = $_FILES['solution'];
    $tmp_name = $solutionFile['tmp_name'];
    $oldname = strtolower($solutionFile['name']);
    $name = strtolower($solutionFile['name']);
    $ext = substr(strrchr($name, "."), 1);
    $file_name = date("Ymdhisu");
   	$error = move_uploaded_file($tmp_name, "solutions/$file_name.$ext");
   	$returned_url = "/solutions/$file_name.$ext";
   	
   	sleep(3);
   	
   	echo "<input id='image_url' type='hidden' value='$returned_url' />"; 	
     
?>