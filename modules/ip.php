<?php

define("IP_INTERFACE", "interface");

function _ip(array $opts) {
    $ip = exec("ifconfig {$opts['interface']} | awk '/inet addr/{print substr($2,6)}'");
    $attr = [
        'class' => 'network',
        'status' => (!!$ip)?'up':'down'
    ];
    return panel($ip, $attr, 'ip');
}
module("ip", "Show the IP of a networking device");
option("interface", IP_INTERFACE, "string", "The interface to dispaly", "eth0");
