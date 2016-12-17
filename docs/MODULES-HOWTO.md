Module HowTo
============

Modules are registered with a call to the `module()` function. The signature is:

    module( name, info, [tags] )

For the call to be successful, a function must be defined having the same name but
prefixed with an underscore. For example, to register the module `test`, the function
`_test` must be callable.

This function takes a single argument, an array of options, as defined by the module.
It then returns a `new Panel` with value and settings applied.

## Options

Options are defined using the `options()` function:

    option( name, mapped-to, type, info, [default] )

The `mapped-to` option allows for different names on the command line/configuration
and in the actual passed option. If `NULL`, the value of `name` will be used.

# Returning panels

Return panels with the `panel()` helper:

    panel( text, [attr] )

The attributes should be an array. It can define anything a theme can, plus the 
desired `class`:

    panel($status,['class'=>'vcs']);

## Example

A module that displays the time:

    <?php

        // Defining our options is handy, makes for cleaner code
        define("TIME_FORMAT_24H", "24h");
        define("TIME_SHOW_SECONDS", "seconds");

        // This is the actual module function
        function _time(array $opts) {
            $fmt =  ($opts[TIME_FORMAT_24H]?'H:i':'h:i').
                    ($opts[TIME_SHOW_SECONDS]?':s':'').
                    ($opts[TIME_FORMAT_24H]?'':'p');
            return
            panel(date($fmt));
        }

        // And this is how the module is registered, together with its two options
        module("time", "Show the current time", [ "bling", "time" ]);
        option("24h", TIME_FORMAT_24H, "bool", "If true, use 24 hours time", false);
        option("seconds", TIME_SHOW_SECONDS, "bool", "Show seconds", false);

