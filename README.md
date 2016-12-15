PowerLine: Customizable bash prompt
===================================



## Installing

To install, copy this directory to `~/.powerline`. Then source the `powerline.sh` script from
your `.bashrc`:

    $ cp -R src ~/.powerline
    $ echo 'source ~/.powerline/powerline.sh' >> ~/.bashrc

To activate, reopen your console or source your `.bashrc` again:

    $ source ~/.bashrc


## Editing

Add git status in the best position:

    $ phpl-edit -igit 

Add git status to beginning of prompt:

    $ phpl-edit -igit --first

Use `-h` for help.
