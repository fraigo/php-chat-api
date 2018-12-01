<?php
header("Content-type: text/json");

if ($action=="register"){
    if (!validEmail($id)){
        responseHeader(400,"Bad request");
        die();
    }
    $user=[
        "email" => $id,
        "imageUrl" => "",
        "name" => $id
    ];
    if (file_exists("users/$id")){
        $ser=(file_get_contents("users/$id"));
        $user=unserialize(base64_decode($ser));
    }
    @$user["imageUrl"]=$_REQUEST["imageUrl"]?:$user["imageUrl"];
    @$user["name"]=$_REQUEST["name"]?:$user["name"];
    file_put_contents("users/$id",base64_encode(serialize($user)));
    echo json_encode($user);
}

if ($action=="get"){
    $user=[];
    if (!validEmail($id)){
        responseHeader(400,"Bad request");
        die();
    }
    if (file_exists("users/$id")){
        $ser=(file_get_contents("users/$id"));
        $user=unserialize(base64_decode($ser));
    }
    echo json_encode($user);
}
