<?php

header("Content-type: text/plain");

function reportFile($dir){
    $content= dirContent($dir);
    foreach($content as $file){
        $lines= fileLines("$dir/$file");
        echo "\n$dir/$file\n\n";
        foreach($lines as $line){
            $data=unserialize(base64_decode($line));
            print_r($data);
            echo "\n";
        }
    }
}


reportFile("data/users");
reportFile("data/chats");
reportFile("data/senders");

