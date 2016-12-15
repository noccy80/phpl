<?php

define("TEXT_TEXT", "text");

function _text(array $opts) {
    $attr = [];
    if ($opts['class']) $attr['class'] = $opts['class'];
    return panel($opts['text'], $attr, 'text');
}
module("text", "Show a text");
option("text", TEXT_TEXT, "string", "The text to dispaly", "Text");
