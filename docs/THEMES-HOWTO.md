Themes HowTo
============


## Definitions

    * - any, use to set defaults
    .X - any item with class X
    Y - any item of type Y
    Y:Z - any item of type Y with status Z
    Y.X - any item of type Y with class X
    .X:Z - any of class X with status Z
    ...

Examples:

    .vcs            // Any item having class 'vcs'
    git.vcs         // A git panel having the class 'vcs'
    status:good     // Status ($) when all went ok
    status:bad      // Status ($) when someting failed
    :good           // Everything that is good


## Attributes

Padding before text:

    pad-before: {none|<int>|<string>}
    pad-before: 1;

Padding after text:
    
    pad-after: {none|<int>|<string>}
    pad-after: 1;

Foreground color:
    
    color: {none|<colorname>|<hex>}
    color: #000000;

Background color:
    
    background: {none|<colorname>|<hex>}
    background: white;

Text style (separate multiple with space):

    style: {none,bold,italic,underline}
    style: bold italic;

## Variables

 * To set: `$set(<name>,<value>)`
 * To reference: `%<name>`

    $set(mycolor,red);
    .class {
        color: %mycolor;
    }

Example:

    set(pretty,#4488BB)
    color: %pretty

## Including other styles

 * `$import(<file>)`: Include the file *if it can be found*. Will not report errors.
 * `$include(<file>)`: Include the specified file, error out if it can not be found.

You can use these two directives to create a theme that uses shared components and allow
the user to modify aspects of it by creating the appropriate file:

    // This is mytheme-white.theme
    $include(mytheme.common)
    $import(mytheme.custom)
    * { background: white; }

The file `mytheme.custom` will only be included if it is found alongside `mytheme-white.theme`
while the file `mytheme.common` will always be included.

## Pragmas

Don't use the pragmas. They will change a lot.

 * `$pragma(256color)`: Used to enable 256-color mode
 * `$pragma(truecolor)`: Used to enable 16.7m color mode

## Overriding icons

You can override icons with the `$icon(name,icon)` directive. The following will
use the letter "Y" as the branch icon for the git module. Unicode is allowed. Do not quote
the icon string as it is not parsed by the tokenizer but in a preprocessing step. This
also means that you can not use the character `)` as an icon right now.

    $icon(git.branch,Y)