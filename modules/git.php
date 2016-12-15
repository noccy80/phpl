<?php

// Icons
define("ICON_GIT_BRANCH_SYMBOL",""); // "⑂");
define("ICON_GIT_BRANCH_CHANGED_SYMBOL"," "); // "); //🗘");
define("ICON_GIT_BRANCH_CURRENT","");
define("ICON_GIT_NEED_PUSH_SYMBOL",""); // ⇡");
define("ICON_GIT_NEED_PULL_SYMBOL",""); // "⇣");
define("ICON_GIT_TAG"," ");

// Options
define("GIT_SHOW_TAG", "show_tag");
define("GIT_SHOW_STATUS", "show_status");

// Module
function _git(array $opts=[]) {

    $git = "env LANG=C git";

    $branch = exec("{$git} symbolic-ref --short HEAD 2>/dev/null");
    if (!$branch)
        return null;

    $marks = [];

    if ($opts[GIT_SHOW_STATUS]==true) {
        exec("{$git} status --porcelain --branch", $status);
        if (count($status)>1)
            $marks[] = ICON_GIT_BRANCH_CHANGED_SYMBOL;
        else
            $marks[] = ICON_GIT_BRANCH_CURRENT;
        if (preg_match('/\[ahead ([0-9]+)\]/', $status[0], $match))
            $marks[] = ICON_GIT_NEED_PUSH_SYMBOL.$match[1];
        if (preg_match('/\[behind ([0-9]+)\]/', $status[0], $match))
            $marks[] = ICON_GIT_NEED_PULL_SYMBOL.$match[1];
    }

    if ($opts[GIT_SHOW_TAG]==true) {
        $tag = exec("{$git} describe --tags 2>/dev/null");
        if ($tag)
            $marks[] = ICON_GIT_TAG.(strpos($tag,'-')?substr($tag,0,strpos($tag,'-')).'+':$tag);
    }

    return panel(sprintf("%s %s %s", ICON_GIT_BRANCH_SYMBOL, $branch, join(" ",$marks)), ['class'=>'vcs'], 'git');
}

module("git", "Git VCS status", [ "git", "vcs" ]);
option("tag", GIT_SHOW_TAG, OPT_TYPE_BOOL, "Show tag", false);
option("status", GIT_SHOW_STATUS, OPT_TYPE_BOOL, "Show branch and status", true);