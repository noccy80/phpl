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
function _git(array $opts) {

    $git = "env LANG=C git";
    if (!is_dir(".git"))
        return null;

    $branch = exec("{$git} symbolic-ref --short HEAD 2>/dev/null");
    //if (!$branch)
    //    return null;

    $marks = [];

    if ($opts[GIT_SHOW_STATUS]==true) {
        exec("{$git} status --porcelain --branch", $status);
        if (count($status)>1)
            $marks[] = icon("git.is_changed");
        else
            $marks[] = icon("git.is_current");
        if (preg_match('/\[ahead ([0-9]+)\]/', $status[0], $match))
            $marks[] = icon("git.need_push").$match[1];
        if (preg_match('/\[behind ([0-9]+)\]/', $status[0], $match))
            $marks[] = icon("git.need_pull").$match[1];
    }

    if ($opts[GIT_SHOW_TAG]) {
        $tag = exec("{$git} describe --tags 2>/dev/null");
        if ($tag)
            $marks[] = icon("git.tag").(strpos($tag,'-')?substr($tag,0,strpos($tag,'-')).'+':$tag);
    }

    $out = sprintf("%s%s %s", icon('git.branch'), $branch, join(" ",$marks));

    return panel($out, ['class'=>'vcs'], 'git');
}

module("git", "Show status from git repositories", [ "git", "vcs" ]);
option("tag", GIT_SHOW_TAG, OPT_TYPE_BOOL, "Show tag", false);
option("status", GIT_SHOW_STATUS, OPT_TYPE_BOOL, "Show branch and status", true);
seticon("git.branch"," "); // "⑂");
seticon("git.is_changed"," "); // "); //🗘");
seticon("git.is_current","");
seticon("git.need_push",""); // ⇡");
seticon("git.need_pull",""); // "⇣");
seticon("git.tag"," ");
