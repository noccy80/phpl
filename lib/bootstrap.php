<?php

define("PHPL_MODULES", __DIR__."/../modules");
define("PHPL_THEMES", __DIR__."/../themes");
define("PHPL_CACHE_DIR", __DIR__."/../cache");
define("PHPL_STATE", __DIR__."/../cache/current");
define("PHPL_CACHE_PROMPT", __DIR__."/../cache/prompt.dat");
define("PHPL_CACHE_THEME", __DIR__."/../cache/theme.dat");
define("PHPL_CONFIG", __DIR__."/../phpl.conf");

if (!is_dir(PHPL_CACHE_DIR)) {
    mkdir(PHPL_CACHE_DIR);
}

/**
 * Parse the command line and match the arguments in $optstr.
 * If an option in longopts is indexed with the key of an option,
 * any values of the short option will be set under the same key
 * as the long option.
 *
 * @param string $optstr The option string
 * @param array $longopts Long options
 */
function cmdl_parse($optstr, array $longopts=[]) {
    // Parse options, no need to reinvent the wheel.
    $parsed = getopt($optstr, array_values($longopts));

    $result = [];
    foreach ($parsed as $opt=>$val) {
        $key = array_key_exists($opt,$longopts)?rtrim($longopts[$opt],':'):$opt;
        $result[$key] = $val;
    }

    return $result;
}

/**
 * Helper to get a value from a parsed command line
 *
 * @param array $parsed The parsed command line
 * @param string $key The key
 */
function cmdl_get($parsed, $key) {
    if (!array_key_exists($key, $parsed)) {
        return false;
    }
    if (is_array($parsed[$key])) {
        if ($parsed[$key][0]===false) {
            return count($parsed[$key]);
        }
    }
    if ($parsed[$key]===false) {
        return true;
    }
    return $parsed[$key];
}
