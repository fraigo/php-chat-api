<?php

function validEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function getHeader($name){
    $headers = apache_request_headers();
    return @$headers[$name];
}

function validUser($email){
    if (!validEmail($email)){
        return false;
    }
    $id_token = @$_REQUEST["id_token"];
    if (!$id_token){
        $auth=getHeader("Authentication");
        if ($auth){
            list($type,$id_token) = explode(" ",$auth);
        }
    }
    $result=false;
    if ($id_token){
        $result=validToken($id_token,$email);
    }
    if ($result){
        return true;
    }else{
        responseHeader(403,"Unauthorized");
        die();
    }
}

function response($controller, $action, $id, $content){
    $data=file_get_contents("php://input");
    $file="./controllers/$controller.php";
    if (file_exists($file)){
        include($file);
    }else{
        responseHeader(400,'Bad Request');
        die();
    }
    
}

function responseHeader($code,$message){
    header("HTTP/1.1 $code $message");
}

function responseJson($data){
    header("Content-type: text/json");
    echo json_encode($data);
}


function dirContent($dir){
    $files=scandir($dir);
    $ignore=[".", "..", ".gitignore",".DS_Store"];
    $result=[];
    foreach($files as $file){
        if (!in_array($file,$ignore)){
            $result[]=$file;
        }
    }
    return $result;
}

function fileLines($file){
    return explode("\n",file_get_contents($file));
}

function validToken($id_token,$email){
    $CLIENT_ID="774131575761-qimgujhl8d3ppsp9l097bcnue8u18h58.apps.googleusercontent.com";
    $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
    $payload = $client->verifyIdToken($id_token);
    if ($payload) {
        return $payload["email"] = $email;
        // If request specified a G Suite domain:
        //$domain = $payload['hd'];
    } else {
        return null;
    }
}