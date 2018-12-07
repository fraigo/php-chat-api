<?php
header("Content-type: text/json");

if ($action=="register"){
    if (!validUser($id)){
        responseHeader(400,"Bad request");
        die();
    }
    $user=[
        "email" => $id,
        "imageUrl" => "",
        "name" => $id
    ];
    if (file_exists("data/users/$id")){
        $ser=(file_get_contents("data/users/$id"));
        $user=unserialize(base64_decode($ser));
    }else{
        createMessage($id,"echo@imessenger.com",$id,"Welcome to iMessenger.\nThis is an automated echo service.");
    }
    @$user["imageUrl"]=$_REQUEST["imageUrl"]?:$user["imageUrl"];
    @$user["name"]=$_REQUEST["name"]?:$user["name"];
    file_put_contents("data/users/$id",base64_encode(serialize($user)));
    createSender($id,"echo@imessenger.com","Echo Service");
    createSender("echo@imessenger.com",$id);
    echo json_encode($user);
}

if ($action=="get"){
    $user=[];
    if (!validUser($id)){
        responseHeader(400,"Bad request");
        die();
    }
    if (file_exists("data/users/$id")){
        $ser=(file_get_contents("data/users/$id"));
        $user=unserialize(base64_decode($ser));
    }
    echo json_encode($user);
}
