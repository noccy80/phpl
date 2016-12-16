<?php

define("USERNAME_CLASS", "class");

function _username(array $opts) {
    $attr = [
        'class' => $opts[USERNAME_CLASS]
    ];
    $user = trim(exec("whoami"));
    return panel($user, $attr, 'username');
}
module("username", "Username");
option("class", USERNAME_CLASS, "string", "The class to use", "user");