<?php

header("Content-type: text/json");

if ($action == "get"){
    $result = [];
    if (!validEmail($id)){
        responseHeader(400,"Bad request");
        die();
    }
    if (file_exists("senders/$id")){
        $ser=(file_get_contents("senders/$id"));
        $result=unserialize(base64_decode($ser));
    }
    echo json_encode(array_values($result));
}
