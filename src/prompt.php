<?php

// Import modules
module("common");
module("git");

$style_path = style(BR_WHITE, CYAN);
$style_vcs = style(BR_WHITE, BLUE);

add("path", $style_path, [
    PATH_SHORTEN => true,
    PATH_LENGTH => 40,
]);
add("git", $style_vcs);
add("status", EXIT_STATUS?style(BR_WHITE,RED):style(BR_WHITE,GREEN));
