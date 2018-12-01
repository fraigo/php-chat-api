<?php

$result=[];
if (file_exists("data/$action") && is_dir("data/$action")){
    $files=scandir("data/$action");
    foreach($files as $file){
        echo $file;
        if (strpos($file,"@")>0){
            unlink("data/$action/$file");
            $result[] = "data/$action/$file";
        }
    }
}
echo json_encode($result);
