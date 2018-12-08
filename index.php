<?php

require("vendor/autoload.php");
require("utils.php");

$PATHINFO= @$_SERVER["PATH_INFO"];
if (!$PATHINFO){
    $PATHINFO= $_SERVER["REQUEST_URI"];
}

@list($base,$controller,$action,$id, $content) = explode("/", $PATHINFO);

response($controller,$action, $id, $content);

