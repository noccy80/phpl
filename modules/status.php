<?php

define("STATUS_CHARACTER", "character");
define("STATUS_CHARACTER_ROOT", "character_root");
define("STATUS_STYLE_GOOD", "style_good");
define("STATUS_STYLE_BAD", "style_bad");

function _status(array $opts=[]) {
    $opts = array_merge([
        STATUS_CHARACTER => '$',
        STATUS_CHARACTER_ROOT => '#',
        STATUS_STYLE_GOOD => style(BR_WHITE,GREEN),
        STATUS_STYLE_BAD => style(BR_WHITE,RED)
    ], $opts);
    
    $character = (posix_getuid()==0)?$opts[STATUS_CHARACTER_ROOT]:$opts[STATUS_CHARACTER];
    $style = (LAST_STATUS)?$opts[STATUS_STYLE_BAD]:$opts[STATUS_STYLE_GOOD];
    
    return panel( $character, [ 'class'=>'shell', 'status' => ((LAST_STATUS>0)?'bad':'good') ], 'status' );
}

module("status", "Last command status and uid/root indicator");
