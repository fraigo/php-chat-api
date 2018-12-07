<?php

function validEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function getHeader($name){
    $headers = getallheaders();
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
    $id_client = @$_REQUEST["client"];
    if (!$id_client){
        $id_client=getHeader("Client");
    }
    $result=false;
    if ($id_token){
        $result=validToken($id_token,$id_client,$email);
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

function validToken($id_token,$CLIENT_ID,$email){
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


if (!function_exists('getallheaders')) 
{ 
    function getallheaders() 
    { 
           $headers = []; 
       foreach ($_SERVER as $name => $value) 
       { 
           if (substr($name, 0, 5) == 'HTTP_') 
           { 
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
           } 
       } 
       return $headers; 
    } 
} 


function createMessage($store, $from, $to, $message){
    $f=fopen("data/chats/$store","a");
    $result = [];
    $result["from"]=$from;
    $result["to"]=$to;
    $result["timestamp"]=time();
    $result["visible"]=1;
    $result["message"]=$message;
    fwrite($f,base64_encode(serialize($result)));
    fwrite($f,"\n");
    fclose($f);
}

function createSender($from,$to,$name=null){
    $senders=[];
    if (file_exists("data/senders/$from")){
        $ser=(file_get_contents("data/senders/$from"));
        $senders=unserialize(base64_decode($ser));
    }
    $sender=@$senders[$to];
    if (!$sender){
        $sender=[];
        $sender["id"]=10000+count($senders);
        $sender["email"]=$to;
        $sender["name"]=$name?:$to;
        $sender["number"]="";
        $sender["imageUrl"]="";
    }
    if (file_exists("data/users/$to")){
        $ser=(file_get_contents("data/users/$to"));
        $user=unserialize(base64_decode($ser));
        $sender["name"]=$user["name"];
        $sender["imageUrl"]=$user["imageUrl"];
    }
    $senders[$to] = $sender;
    file_put_contents("data/senders/$from",base64_encode(serialize($senders)));  
}