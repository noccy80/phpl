Themes HowTo
============


## Definitions

    * - any, use to set defaults
    .X - any item with class X
    Y - any item of type Y
    Y:Z - any item of type Y with status Z
    Y.X - any item of type Y with class X

Examples:

    .vcs            // Any item having class 'vcs'
    git.vcs         // A git panel having the class 'vcs'
    status:good     // Status ($) when all went ok
    status:bad      // Status ($) when someting failed


## Attributes

    pad-before: {none|<int>|<string>}
    Padding before text
    
    pad-after: {none|<int>|<string>}
    Padding after text
    
    color: <color>
    Foreground color
    
    background: <color>
    Background color


## Variables

 * To set: `$set(<name>,<value>)`
 * To reference: `%<name>`

Example:

    set(pretty,#4488BB)
    color: %pretty

## Including

 * `$include(<file>)`: Include the specified file

## Pragmas

 * `$pragma(256color)`: Used to enable 256-color mode
 * `$pragma(16mcolor)`: Used to enable 16.7m color mode

