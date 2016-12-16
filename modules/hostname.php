<?php

define("HOSTNAME_CLASS", "class");

function _hostname(array $opts) {
    $attr = [
        'class' => $opts[HOSTNAME_CLASS]
    ];
    $host = trim(exec("whoami"));
    return panel($host, $attr, 'hostname');
}
module("hostname", "Hostname");
option("class", HOSTNAME_CLASS, "string", "The class to use", "system");