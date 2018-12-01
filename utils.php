<?php

function validEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}



function response($controller, $action, $id, $content){
    $data=file_get_contents("php://input");
    $file="./$controller.php";
    if (file_exists($file)){
        include($file);
    }
    
}

function responseHeader($code,$message){
    header("HTTP/1.1 $code $message");
}
