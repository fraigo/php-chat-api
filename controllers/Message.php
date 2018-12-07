<?php

function get($id,$rec=""){
    $email=strtolower($id);
    if (!validUser($email)){
        responseHeader(400,"Bad request");
        die();
    }
    $result = [];
    if (file_exists("data/chats/$email")){
        $messages=explode("\n",file_get_contents("data/chats/$email"));
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
    $from=strtolower($id);
    $to=strtolower($_REQUEST["to"]);
    if (!validUser($from)){
        responseHeader(400,"Bad request");
        responseJson([
            "error" => "Invalid user"
        ]);
        die();
    }
    if (!validEmail($to)){
        responseHeader(400,"Bad request");
        responseJson([
            "error" => "Invalid email recipient"
        ]);
        die();
    }
    $timestamp=time();
    $message=$_REQUEST["message"];

    createMessage($to, $from, $to, $message);
    createSender($to,$from);
    
    if ($from != $to){
        createMessage($from, $from, $to, $message);
        createSender($from,$to);  
    }
    messageResponder($from,$to,$message);
    
    $result["timestamp"]=$timestamp;
    responseJson($result);
}


if (function_exists($action)){
    echo $action($id,$content);
}else{
    responseHeader(400,'Bad Request');
    die();
}