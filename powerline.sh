

function _updateprompt {
    export BASH_CWD="$(pwd)"
    export PS1="$(phpl-generate -s $? -d $BASH_CWD) "
}

export PROMPT_COMMAND="_updateprompt"
