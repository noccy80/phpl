PowerLine: Customizable bash prompt
===================================



## Installing

To install, copy the `src` directory to `~/.powerline`. Then source the `powerline.sh` script from
your `.bashrc`:

    $ cp -R src ~/.powerline
    $ echo 'source ~/.powerline/powerline.sh' >> ~/.bashrc

To activate, reopen your console or source your `.bashrc` again:

    $ source ~/.bashrc


## Configuring

To configure, edit the `prompt.php` file in `~/.powerline`.

The `module($mod)` command is used to import modules. The `style($fg,$bg,$attr)` command returns an
anonymous function to apply the style to any text. And finally, the `add($mod,$style,$args)`
command to add an actual panel to the prompt.

    // Note, there is no ipaddress module, but if there was it would
    // probably work like this. Load it using module()
    module('ipaddress');

    // Add the module, with the desired horrible style choice and options :)
    add('ipaddress', style(PURPLE,BR_RED), [ IPADDRESS_DEVICE=>'eth0' ]);

## Writing modules

Put your module in `modules` named aptly, then define your module as a function with a `mod_` prefix.

    function mod_quote() {
        return "This is a quote";
    }
    add('quote', ...)
