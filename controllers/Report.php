<?php


function reportFile($dir){
    $content= dirContent($dir);
    $result=[];
    foreach($content as $file){
        $lines= fileLines("$dir/$file");
        $result["$dir/$file"]=[];
        foreach($lines as $line){
            $data=unserialize(base64_decode($line));
            $result["$dir/$file"]=$data;
        }
    }
    return $result;
}

function all(){
    $results=[];
    $results=array_merge($results,reportFile("data/users"));
    $results=array_merge($results,reportFile("data/chats"));
    $results=array_merge($results,reportFile("data/senders"));
    return $results;
}




