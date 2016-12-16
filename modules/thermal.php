<?php

define("THERMAL_FORMAT", "format");
define("THERMAL_CLASS", "class");
define("THERMAL_ZONE", "zone");

function _thermal(array $opts) {
    $attr = [];
    $zone = $opts[THERMAL_ZONE];
    $temp = (int)@trim(file_get_contents("/sys/class/thermal/{$zone}/temp"));
    $temp = (float)$temp/1000;
    $text = sprintf("🌡 ".$opts[THERMAL_FORMAT], $temp);
    $attr['class'] = $opts[THERMAL_CLASS];
    return panel($text, $attr, 'thermal');
}
module("thermal", "Show thermal zone info");
option("zone", THERMAL_ZONE, OPT_TYPE_STRING, "Thermal zone (from /sys/class/thermal)", "thermal_zone0");
option("format", THERMAL_FORMAT, OPT_TYPE_STRING, "Format", "%.1fºc");
option("class", THERMAL_CLASS, OPT_TYPE_STRING, "The class to use", "system");
