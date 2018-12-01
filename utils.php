<?php

function validEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}



function response($controller, $action, $id, $content){
    $data=file_get_contents("php://input");
    $file="./controllers/$controller.php";
    if (file_exists($file)){
        include($file);
    }else{
        responseHeader(400,'Bad Request');
        die();
    }
    
}

function responseHeader($code,$message){
    header("HTTP/1.1 $code $message");
}


function dirContent($dir){
    $files=scandir($dir);
    $ignore=[".", "..", ".gitignore",".DS_Store"];
    $result=[];
    foreach($files as $file){
        if (!in_array($file,$ignore)){
            $result[]=$file;
        }
    }
    return $result;
}

function fileLines($file){
    return explode("\n",file_get_contents($file));
}