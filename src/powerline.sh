

export POWERLINE="$(cd $(dirname $BASH_SOURCE) && pwd)"
function _updateprompt {
    export BASH_CWD="$(pwd)"
    export PS1="$($POWERLINE/genprompt $? $BASH_CWD)"
}

export PROMPT_COMMAND="_updateprompt"
