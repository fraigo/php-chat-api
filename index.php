<?php


@list($base,$controller,$action,$id, $content) = explode("/",$_SERVER["PATH_INFO"]);


function response($controller, $action, $id, $content){
    $data=file_get_contents("php://input");
    include("$controller.php");
}


echo response($controller,$action, $id, $content);
