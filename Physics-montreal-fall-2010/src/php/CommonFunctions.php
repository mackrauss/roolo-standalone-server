<?php
function deleteDirContents($dirpath){
	foreach(glob($dirpath.'/*') as $v){
		echo "$v\n";
	    delete_directory($v);
	}
}

function delete_directory($dirname) {
   if (is_dir($dirname))
      $dir_handle = opendir($dirname);
   if (!$dir_handle)
      return false;
   while($file = readdir($dir_handle)) {
      if ($file != "." && $file != "..") {
         if (!is_dir($dirname."/".$file))
            unlink($dirname."/".$file);
         else
            delete_directory($dirname.'/'.$file);    
      }
   }
   closedir($dir_handle);
   rmdir($dirname);
   return true;
}

function convertToId($name){
	
	
	// remove all non-alphanumeric chars
	$cleanStr = preg_replace('/\W/u', '', $name);
	
	// trim spaces at beginning or end of string
	$cleanStr = trim($cleanStr);
	
	// collapse each sequenece of whitespace to a single space and replace all spaces by a dash(-)
//	$cleanStr = preg_replace('/\s+/u', '-', $cleanStr);
	
	// ensure everything's in lowercase
	$cleanStr = mb_strtolower($cleanStr);
	
	return $cleanStr;
}
?>