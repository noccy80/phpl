

function _updateprompt {
    export LAST=$?
    export BASH_CWD="$(pwd)"
    export PS1="$(phpl-generate -s $LAST -d $BASH_CWD) "
}

export PROMPT_COMMAND="_updateprompt"
