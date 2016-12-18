<?php

define("COMMAND_CLASS", "class");
define("COMMAND_EXEC", "exec");
define("COMMAND_ICON", "icon");

function _command(array $opts) {
    $attr = [
        'class' => $opts[COMMAND_CLASS]
    ];
    if (($exec = $opts[COMMAND_EXEC])) {
        $output = trim(exec($exec));
    } else {
        $output = null;
    }
    if (!empty($opts[COMMAND_ICON])) {
        $output = icon($opts[COMMAND_ICON]).$output;
    }
    return panel($output, $attr, 'command');
}
module("command", "Execute a command and display the output");
option("exec",COMMAND_EXEC,OPT_TYPE_STRING,"Command to execute","");
option("class",COMMAND_CLASS,OPT_TYPE_STRING,"The class to use","system");
option("icon",COMMAND_ICON,OPT_TYPE_STRING,"Name of custom icon to be put in front of the output","");