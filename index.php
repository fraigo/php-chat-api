<?php

require("vendor/autoload.php");
require("utils.php");

@list($base,$controller,$action,$id, $content) = explode("/",$_SERVER["PATH_INFO"]);

echo response($controller,$action, $id, $content);

