PHPl: The Ultimate PHP Powerline
================================

This is a highly customizable powerline prompt generator for PHP. While being 100%
modular, the configuration is processed into a compact ready-to-use cache. Themes
are extremely dynamic, making use of a CSS-like syntax to add styles to panels based
on type, status or class, with 256-color support. And everything can be configured
from the command line.

## Installing

The recommended method of installing is using the installer, or by using the 
`install.sh` script after cloning into a temporary directory. If *phpl* is already
installed, it will be updated.

## Configuration

The initial prompt will display the directory and a status indicator:

    ~  $

To edit the prompt, use the `phpl-config` command. To make it a bit more boring:

    $ phpl-config -i username --first --no-reload
    $ phpl-config -i text --after username -s text="@" --no-reload
    $ phpl-config -i hostname --after text

The prompt will now look like:

    user  @  hostname  $

## Appearance

To make the prompt less fluffy, you can create a compact style. Create `~/.powerline/theme/compact.theme`:

    * {
        pad-before: 0;
        pad-after: 0;
    }

Activate the theme using `phpl-config`:

    $ phpl-config --theme compact

To add some color to the prompt:

    username hostname {
        color: bright-blue;
    }
    text {
        color: white;
    }
    status:good {
        color: green;
    }
    status:bad {
        color: red;
    }

This will have the following result:

 *  The `username` and `hostname` parts will be colored bright blue
 *  All `text` items will be colored white
 *  The status indicator (`$`) will be colored green if the last command was successful, and
    red otherwise.

## More commands

You can list all the available modules using `phpl-config -L`:

    $  phpl-config -L
    Available modules and options:
    - command: Execute a command and display the output
        = exec<string> - Command to execute (default:'')
        = class<string> - The class to use (default:'system')
    - diskfree: Show disk free space
        = si<boolean> - Use SI magnitudes (MiB,KiB etc) (default:true)
    - git: Git VCS status
        = tag<boolean> - Show tag (default:false)
        = status<boolean> - Show branch and status (default:true)
    ...

You can also list the currently added items using `phpl-config -l`.

### Adding multiples

To add more than one of a module, you need to name them differently. Do this using the
`-n` or `--name` option when inserting the items:

    $ phpl-config -i text -n hello -s text="hello"
    $ phpl-config -i text -n world -s text="world"

### Placement

You can add items to different parts of the prompt:

 *  `--before` places the item before the specified name
 *  `--after` places it after the specified
 *  `--first` places it in the beginning of the prompt
 *  `--last` places it in the absolute end of the prompt
 *  `--best` places it as the second last item of the prompt
