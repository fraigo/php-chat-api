<?php

header("Content-type: text/json");

function get($id){
    $email=strtolower($id);
    $result = [];
    if (!validUser($email)){
        responseHeader(400,"Bad request");
        die();
    }
    if (file_exists("data/senders/$email")){
        $ser=(file_get_contents("data/senders/$email"));
        $result=unserialize(base64_decode($ser));
    }
    echo json_encode(array_values($result));
}


