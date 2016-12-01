<?php

function mod_diskfree(array $opts=[]) {

    $opts = array_merge([
    ], $opts);

    $free = disk_free_space(WORKING_DIR);
    $total = disk_total_space(WORKING_DIR);
    $pc = (100/$total)*$free;


    if ($pc<2) {
        $style = style(BR_RED,GRAY);
    } elseif ($pc<5) {
        $style = style(BR_YELLOW,GRAY);
    } else {
        $style = style(BR_GREEN,GRAY);
    }
    
    $units = [ "B", "KB", "MB", "GB" ];
    do {
        $unit = array_shift($units);
        if (($free < 1024) || (count($units)==0)) {
            $icon = ""; // 

            return [ sprintf("{$icon} %.1f%s", $free, $unit), $style ];
        }
        $free /= 1024;
    } while (true);
}
