<?php

define("PATH_LENGTH", "length");

function _path(array $opts) {
    
    $cwd = WORKING_DIR;
    
    $home = getenv("HOME");
    if (strpos($cwd,$home)===0) {
        $cwd = "~".substr($cwd,strlen($home));
    } elseif ($opts[PATH_LENGTH]>0) {
        if (strlen($cwd)>$opts[PATH_LENGTH]) {
            $cwd = "◂".substr($cwd,-$opts[PATH_LENGTH]);
        }
    }
    
    return panel($cwd, [], 'path');
}
module("path", "Show the current working directory");
option("length", PATH_LENGTH, "int", "If greater than 0, max length of displayed path", 0);
