<?php

define("GIT_BRANCH_SYMBOL","⑂");
define("GIT_BRANCH_CHANGED_SYMBOL","+");
define("GIT_NEED_PUSH_SYMBOL","⇡");
define("GIT_NEED_PULL_SYMBOL","⇣");

function mod_git() {
    if (!exec("which git"))
        return null;
    
    $git = "env LANG=C git";
    $cmd = "{$git} symbolic-ref --short HEAD 2>/dev/null || {$git} describe --tags --always 2>/dev/null";
    $branch = exec($cmd);
    
    if (!$branch) return null;

    $marks = [];
    $modified = exec("{$git} status --porcelain");
    if ($modified)
        $marks[] = GIT_BRANCH_CHANGED_SYMBOL;

    return sprintf("%s %s %s", GIT_BRANCH_SYMBOL, $branch, join(" ",$marks));
}
