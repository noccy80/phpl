<?php

define("HOSTNAME_CLASS", "class");

function _hostname(array $opts) {
    $attr = [
        'class' => $opts[HOSTNAME_CLASS]
    ];
    $host = trim(exec("hostname"));
    return panel($host, $attr, 'hostname');
}
module("hostname", "Show the hostname of the system");
option("class", HOSTNAME_CLASS, "string", "The class to use", "system");
