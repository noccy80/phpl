<?php

define("PATH_LENGTH", "path_length");
define("PATH_SHORTEN", "path_shorten");

function mod_status() {
    return "$";
}

function mod_path(array $opts=[]) {
    
    $opts = array_merge([
        PATH_SHORTEN => false,
        PATH_LENGTH => 100
    ], $opts);
    
    $cwd = WORKING_DIR;
    
    $home = getenv("HOME");
    if (strpos($cwd,$home)===0) {
        $cwd = "~".substr($cwd,strlen($home));
    } elseif ($opts[PATH_SHORTEN]) {
        if (strlen($cwd)>$opts[PATH_LENGTH]) {
            $cwd = "...".substr($cwd,-$opts[PATH_LENGTH]);
        }
    }
    
    return $cwd;
}
