<?php


function register($id){
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

    $contacts= getContacts();
    file_put_contents("data/contacts/$email",base64_encode(serialize($contacts)));
    $user["contacts"] = $contacts;
    responseJson($user);
}

function get($id){
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
    responseJson($user);
}

