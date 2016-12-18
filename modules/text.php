<?php

define("TEXT_TEXT", "text");
define("TEXT_CLASS", "class");
define("TEXT_ICON", "icon");

function _text(array $opts) {
    $attr = [];
    if ($opts[TEXT_CLASS]) $attr['class'] = $opts[TEXT_CLASS];
    $icon = (!empty($opts[TEXT_ICON]))?icon($opts[TEXT_ICON]):"";
    return panel($icon.$opts[TEXT_TEXT], $attr, 'text');
}
module("text", "Show a static text");
option("text", TEXT_TEXT, "string", "The text to dispaly", "Text");
option("class", TEXT_CLASS, "string", "The class to use", "info");
option("icon", TEXT_ICON,OPT_TYPE_STRING,"Name of custom icon to be put in front of the output","");