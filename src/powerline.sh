

export POWERLINE="$(cd $(dirname $0) && pwd)"
function _updateprompt {
    export PS1="$($POWERLINE/genprompt $? $(pwd))"
}

export PROMPT_COMMAND="_updateprompt"
