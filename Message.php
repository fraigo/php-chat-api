<?php
header("Content-type: text/json");

if ($action == "get"){
    $result = [];
    if (file_exists("chats/$id")){
        $messages=explode("\n",file_get_contents("chats/$id"));
        foreach($messages as $message){
            if ($message!=""){
                $result[]=unserialize(base64_decode($message));
            }
        }
    }
    echo json_encode($result);
}
if ($action == "push"){
    
    $timestamp=time();
    $from=$_REQUEST["from"];
    $message=$_REQUEST["message"];

    $f=fopen("chats/$id","a");
    $result = [];
    $result["from"]=$from;
    $result["to"]=$id;
    $result["timestamp"]=time();
    $result["visible"]=1;
    $result["message"]=$message;
    fwrite($f,base64_encode(serialize($result)));
    fwrite($f,"\n");
    fclose($f);

    $senders=[];
    if (file_exists("senders/$id")){
        $ser=(file_get_contents("senders/$id"));
        $senders=unserialize(base64_decode($ser));
    }
    $sender=@$senders[$from];
    if (!$sender){
        $sender=[];
        $sender["id"]=10000+count($senders);
        $sender["email"]=$from;
        $sender["name"]=$from;
        $sender["number"]="";
    }
    if (file_exists("users/$from")){
        $ser=(file_get_contents("users/$from"));
        $user=unserialize(base64_decode($ser));
        $sender["name"]=$user["name"];
    }
    $senders[$from] = $sender;
    file_put_contents("senders/$id",base64_encode(serialize($senders)));    

    $f=fopen("chats/$from","a");
    $result = [];
    $result["from"]=$from;
    $result["to"]=$id;
    $result["timestamp"]=time();
    $result["visible"]=1;
    $result["message"]=$message;
    fwrite($f,base64_encode(serialize($result)));
    fwrite($f,"\n");
    fclose($f);

    $senders=[];
    if (file_exists("senders/$from")){
        $ser=(file_get_contents("senders/$from"));
        $senders=unserialize(base64_decode($ser));
    }
    $sender=@$senders[$id];
    if (!$sender){
        $sender=[];
        $sender["id"]=10000+count($senders);
        $sender["email"]=$id;
        $sender["name"]=$id;
        $sender["number"]="";
    }
    if (file_exists("users/$id")){
        $ser=(file_get_contents("users/$id"));
        $user=unserialize(base64_decode($ser));
        $sender["name"]=$user["name"];
    }
    $senders[$id] = $sender;
    file_put_contents("senders/$from",base64_encode(serialize($senders)));    

    $result["timestamp"]=$timestamp;
    echo json_encode($result);
}