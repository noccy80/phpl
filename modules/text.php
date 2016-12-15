<?php

define("TEXT_TEXT", "text");
define("TEXT_CLASS", "class");

function _text(array $opts) {
    $attr = [];
    if ($opts[TEXT_CLASS]) $attr['class'] = $opts[TEXT_CLASS];
    return panel($opts[TEXT_TEXT], $attr, 'text');
}
module("text", "Show a text");
option("text", TEXT_TEXT, "string", "The text to dispaly", "Text");
option("class", TEXT_CLASS, "string", "The class to use", "");