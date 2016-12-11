<?php

// Import modules
module("common");
module("git");
module("diskfree");

// Define styles
$style_path = style(BR_WHITE, CYAN);
$style_vcs = style(BR_WHITE, BLUE);


// --- Modules ------------------------------------------------------

// Current working directory
add("path", $style_path, [
    PATH_SHORTEN => true,
    PATH_LENGTH => 40,
]);

// Git status
add("git", $style_vcs, [
    GIT_SHOW_TAG=>true
]);

// Free diskspace
//add("diskfree");


// Current time
// add(function () {
//  return "🕒 ".date('H:i');
// style(BLACK,WHITE)); */

add("loadavg", style(BR_YELLOW,GRAY));

// Status indicator
add("status");
