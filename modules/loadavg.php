<?php


function _loadavg() {
    return panel(sprintf("%s%s",icon('loadavg.load'), sys_getloadavg()[0]),[],'loadavg');
}
module("loadavg", "Display system load average", [ "info", "loadavg" ]);
seticon("loadavg.load"," ");