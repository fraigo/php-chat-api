<?php



function path($action){
    $result=[];
    if (file_exists("data/$action") && is_dir("data/$action")){
        $files=scandir("data/$action");
        foreach($files as $file){
            $result["files"][]=$file;
            if (strpos($file,"@")>0){
                unlink("data/$action/$file");
                $result[] = "data/$action/$file";
            }
        }
    }
    return ($result);
}

function all(){
    $result=[];
    array_merge($result,path("chats"));
    array_merge($result,path("senders"));
    array_merge($result,path("users"));
    return ($result);
}





