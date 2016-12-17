<?php

define("USERNAME_CLASS", "class");

function _username(array $opts) {
    $attr = [
        'class' => $opts[USERNAME_CLASS]
    ];
    $user = trim(exec("whoami"));
    return panel(icon("username.icon").$user, $attr, 'username');
}
module("username", "The currently logged in/active username");
option("class", USERNAME_CLASS, "string", "The class to use", "user");
seticon("username.icon", "");
