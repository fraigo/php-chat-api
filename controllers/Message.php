<?php
header("Content-type: text/json");

if ($action == "get"){
    if (!validEmail($id)){
        responseHeader(400,"Bad request");
        die();
    }
    $result = [];
    if (file_exists("data/chats/$id")){
        $messages=explode("\n",file_get_contents("data/chats/$id"));
        foreach($messages as $message){
            if ($message!=""){
                $result[]=unserialize(base64_decode($message));
            }
        }
    }
    echo json_encode($result);
}
if ($action == "push"){
    if (!validEmail($id)){
        responseHeader(400,"Bad request");
        die();
    }
    $timestamp=time();
    $from=$_REQUEST["from"];
    $message=$_REQUEST["message"];

    $f=fopen("data/chats/$id","a");
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
    if (file_exists("data/senders/$id")){
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
        $sender["imageUrl"]="";   
    }
    if (file_exists("data/users/$from")){
        $ser=(file_get_contents("users/$from"));
        $user=unserialize(base64_decode($ser));
        $sender["name"]=$user["name"];
        $sender["imageUrl"]=$user["imageUrl"];
    }
    $senders[$from] = $sender;
    file_put_contents("data/senders/$id",base64_encode(serialize($senders)));    

    $f=fopen("data/chats/$from","a");
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
    if (file_exists("data/senders/$from")){
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
        $sender["imageUrl"]="";
    }
    if (file_exists("data/users/$id")){
        $ser=(file_get_contents("users/$id"));
        $user=unserialize(base64_decode($ser));
        $sender["name"]=$user["name"];
        $sender["imageUrl"]=$user["imageUrl"];
    }
    $senders[$id] = $sender;
    file_put_contents("data/senders/$from",base64_encode(serialize($senders)));    

    $result["timestamp"]=$timestamp;
    echo json_encode($result);
}