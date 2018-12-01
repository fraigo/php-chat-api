<?php

$files=scandir("data/chats");
foreach($files as $file){
    if (strpos($file,"@")>0){
        unlink("data/chats/$file");
    }
}

$files=scandir("data/senders");
foreach($files as $file){
    if (strpos($file,"@")>0){
        unlink("data/senders/$file");
    }
}

$files=scandir("data/users");
foreach($files as $file){
    if (strpos($file,"@")>0){
        unlink("data/users/$file");
    }
}