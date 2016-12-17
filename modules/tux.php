<?php

define("TUX_CLASS", "class");
define("ICON_TUX1", "🐧");
define("ICON_TUX2", "");

function _tux(array $opts) {
    $attr = [];
    if ($opts[TUX_CLASS]) $attr['class'] = $opts[TUX_CLASS];
    return panel(icon("tux.icon"), $attr, 'text');
}
module("tux", "Add a cute penguin to your prompt");
option("class", TUX_CLASS, "string", "The class to use", "tux");
seticon("tux.icon", "🐧");
