<?php

define("TIME_FORMAT", "format");
define("TIME_CLASS", "class");
define("TIME_ANALOG", "analog");

function _time(array $opts) {
    $attr = [];
    if ($opts[TIME_ANALOG]) {
        $analog = [ '🕛','🕧','🕐','🕜','🕑','🕝','🕒','🕞','🕓','🕟','🕔','🕠','🕕','🕡','🕖','🕢','🕗','🕣','🕘','🕤','🕙','🕥','🕚','🕦' ];
        $hour = (int)date('h'); $minute = (int)date('i'); $ti = $hour * 2 + ($minute>30?1:0);
        $time = $analog[$ti%24]." ";
    } else {
        $time = null;
    }
    if ($opts[TIME_CLASS]) $attr['class'] = $opts[TIME_CLASS];
    return panel($time.@date($opts['format']), $attr, 'time');
}
module("time", "Show the current time and/or date");
option("format", TIME_FORMAT, OPT_TYPE_STRING, "The time format", "H:i");
option("class", TIME_CLASS, OPT_TYPE_STRING, "The class to use", "info");
option("analog", TIME_ANALOG, OPT_TYPE_BOOL, "Show an analog watch", false);