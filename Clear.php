<?php

$files=scandir("chats");
foreach($files as $file){
    if (strpos($file,"@")>0){
        unlink("chats/$file");
    }
}

$files=scandir("senders");
foreach($files as $file){
    if (strpos($file,"@")>0){
        unlink("chats/$file");
    }
}

$files=scandir("users");
foreach($files as $file){
    if (strpos($file,"@")>0){
        unlink("chats/$file");
    }
}