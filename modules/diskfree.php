<?php

define("DISKFREE_UNITS_SI", "units_si");

function _diskfree(array $opts=[]) {

    $free = disk_free_space(WORKING_DIR);
    $total = disk_total_space(WORKING_DIR);
    $pc = (100/$total)*$free;

    $class = ($pc<5)?'low':'good';
    
    $units = [ "B", "KB", "MB", "GB" ];
    do {
        $unit = array_shift($units);
        if (($free < 1024) || (count($units)==0)) {
            $icon = ""; // 

            return panel(sprintf("{$icon} %.1f%s", $free, $unit), [ 'class'=>$class ], 'diskfree' );
        }
        $free /= 1024;
    } while (true);
}

module("diskfree", "Show disk free space", [ "info", "disk" ]);
option("si", DISKFREE_UNITS_SI, OPT_TYPE_BOOL, "Use SI magnitudes (MiB,KiB etc)", true);