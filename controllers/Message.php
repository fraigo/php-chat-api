<?php

function get($id,$rec=""){
    if (!validUser($id)){
        responseHeader(400,"Bad request");
        die();
    }
    $result = [];
    if (file_exists("data/chats/$id")){
        $messages=explode("\n",file_get_contents("data/chats/$id"));
        foreach($messages as $message){
            if ($message!=""){
                $msg=unserialize(base64_decode($message));
                if ($rec=="" || $msg["from"]==$rec || $msg["to"]==$rec){
                    $result[]=$msg;
                }
            }
        }
    }
    responseJson($result);
}
    
function push($id){
    if (!validUser($id)){
        responseHeader(400,"Bad request");
        die();
    }
    $timestamp=time();
    $from=$_REQUEST["from"];
    $message=$_REQUEST["message"];

    createMessage($id, $from, $id, $message);
    createSender($id,$from);
    
    if ($from != $id){
        createMessage($from, $from, $id, $message);
        createSender($from,$id);  
    }
    if ($id=="echo@imessenger.com"){
        $ip = $_SERVER["REMOTE_ADDR"];
        createMessage($from,"echo@imessenger.com",$from,"You sent: ".$message."\nFrom: $ip");
    }
    
    $result["timestamp"]=$timestamp;
    responseJson($result);
}


if (function_exists($action)){
    echo $action($id,$content);
}else{
    responseHeader(400,'Bad Request');
    die();
}