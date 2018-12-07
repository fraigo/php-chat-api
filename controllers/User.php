<?php
header("Content-type: text/json");

if ($action=="register"){
    $email=strtolower($id);
    if (!validUser($email)){
        responseHeader(400,"Bad request");
        die();
    }
    $user=[
        "email" => $email,
        "imageUrl" => "",
        "name" => $email
    ];
    if (file_exists("data/users/$email")){
        $ser=(file_get_contents("data/users/$email"));
        $user=unserialize(base64_decode($ser));
    }else{
        messageResponder($email,"echo@imessenger.com","");
    }
    @$user["imageUrl"]=$_REQUEST["imageUrl"]?:$user["imageUrl"];
    @$user["name"]=$_REQUEST["name"]?:$user["name"];
    file_put_contents("data/users/$email",base64_encode(serialize($user)));
    createSender($email,"echo@imessenger.com","Echo Service");
    createSender("echo@imessenger.com",$email);
    echo json_encode($user);
}

if ($action=="get"){
    $email=strtolower($id);
    $user=[];
    if (!validUser($email)){
        responseHeader(400,"Bad request");
        die();
    }
    if (file_exists("data/users/$email")){
        $ser=(file_get_contents("data/users/$email"));
        $user=unserialize(base64_decode($ser));
    }
    echo json_encode($user);
}
