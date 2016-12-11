<?php

define("PATH_LENGTH", "length");
define("PATH_SHORTEN", "shorten");

define("STATUS_CHARACTER", "character");
define("STATUS_CHARACTER_ROOT", "character_root");
define("STATUS_STYLE_GOOD", "style_good");
define("STATUS_STYLE_BAD", "style_bad");

function mod_status(array $opts=[]) {
    $opts = array_merge([
        STATUS_CHARACTER => '$',
        STATUS_CHARACTER_ROOT => '#',
        STATUS_STYLE_GOOD => style(BR_WHITE,GREEN),
        STATUS_STYLE_BAD => style(BR_WHITE,RED)
    ], $opts);
    
    $character = (posix_getuid()==0)?$opts[STATUS_CHARACTER_ROOT]:$opts[STATUS_CHARACTER];
    $style = (EXIT_STATUS)?$opts[STATUS_STYLE_BAD]:$opts[STATUS_STYLE_GOOD];
    
    return [ $character, $style ];
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
            $cwd = "◂".substr($cwd,-$opts[PATH_LENGTH]);
        }
    }
    
    return $cwd;
}

function mod_loadavg() {

    return sprintf(" %s",sys_getloadavg()[0]);

}
