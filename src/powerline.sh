

export POWERLINE="$(cd $(dirname $BASH_SOURCE) && pwd)"
function _updateprompt {
    export PS1="$($POWERLINE/genprompt $? $(pwd))"
}

export PROMPT_COMMAND="_updateprompt"
