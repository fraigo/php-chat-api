<?php

$file="package.zip";
$target="./";


function unlinkRecursive($dir, $DeleteMe) {
    if(!$dh = @opendir($dir)) return;
    while (false !== ($obj = readdir($dh))) {
        if($obj=='.' || $obj=='..') continue;
        if (!@unlink($dir.'/'.$obj)) unlinkRecursive($dir.'/'.$obj, true);
    }
    closedir($dh);
    if ($DeleteMe){
        @rmdir($dir);
    }
}

function unzipTo($file,$target){
	$zip = new ZipArchive;
	$res = $zip->open($file);

	if ($res === TRUE) {
      for($i = 0; $i < $zip->numFiles; $i++) {
          echo $zip->getNameIndex($i)."\n";
      }
      $zip->extractTo($target);
      $zip->close();
      echo "[$file] extracted to $target";
	 } else {
      echo "Doh! I couldn't open $file [$res] ";
	}
}

function message($msg){
	$time=date("H:i:s");
	echo "\n<h4>$time $msg</h4>\n";
}

message("Uncompressing...");
unzipTo($file,$target);

message("Finishing...");
unlink($file);
unlink(__file__);

?>