<?php


function _loadavg() {
    return panel(sprintf(" %s",sys_getloadavg()[0]),[],'loadavg');
}
module("loadavg", "Display system load average", [ "info", "loadavg" ]);
