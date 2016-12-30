<?php

require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/vartype.php";

use Sdl\Parser\SdlParser;
use Sdl\SdlTag;


define("OPT_TYPE_BOOL", "boolean");
define("OPT_TYPE_INT", "integer");
define("OPT_TYPE_STRING", "string");

$_CONFIG = [
    'items' => [
        // Default items
        ['path',    'path',     [ 'length'=>100                 ]],
        ['status',  'status',   [                               ]],
    ],
    'theme' => 'default'
];
// ['loadavg', 'loadavg',  [ 'sample'=>0                   ]],
// ['git',     'git',      [ 'tag'=>true, 'status'=>true   ]],


$_MODULES = [];
$_CURRENT = null;
$_SOURCE  = null;
$_OPTIONS = [
    // option nane, value, default value, description
    "term.colormode"    => [ 0, VarType::enum(0,[0,1,2]), "Terminal color mode: 0=16 color, 1=256 color mode and 2=true color mode" ],
    "term.forceutf8"    => [ false, VarType::bool(false), "If true, always output UTF-8 even if running on a console" ],
    "cache.enable"      => [ false, VarType::bool(false), "Experimental: Controls whether module output can be cached" ],
    "cache.ttl"         => [ 15, VarType::int(15), "Experimental: Maximum time a module can be cached in seconds" ],
    "daemon.enable"     => [ false, VarType::bool(false), "Experimental: Use a resident daemon to serve up the prompts "],
];

function config_read() {
    assert(PHPL_CONFIG);
    global $_CONFIG,$_OPTIONS;

    if (!file_exists(PHPL_CONFIG)) {
        config_write();
        return;
    }

    $root = SdlParser::parseFile(PHPL_CONFIG);

    $items = $root->getChildrenByTagName('items');
    if (count($items)==0) {
        fprintf(STDERR, "Error: No items block in configuration file!\n");
        exit(1);
    }

    $_CONFIG['items'] = [];
    foreach ($items[0]->getChildren() as $item) {
        $type = $item->getTagName();
        $name = $item->getValue()?:$type;
        $attr = $item->getAttributeStrings();
        $_CONFIG['items'][] = [
            $name, $type, $attr
        ];
    }
    $options = $root->getChildrenByTagName('options');
    if (count($options)>0) {
        foreach ($options[0]->getChildren() as $option) {
            $key = $option->getValue();
            $value = $option->getAttribute("value");
            if (!array_key_exists($key, $_OPTIONS)) {
                printf("Warning: No such global option %s\n", $key);
                continue;
            }
            $_OPTIONS[$key][0] = $value;
        }
    }

    $theme = $root->getChildrenByTagName('theme');
    $theme = end($theme);
    if ($theme) {
        $_CONFIG['theme'] = $theme->getValue();
    }
}

function config_write() {
    assert(PHPL_CONFIG);
    global $_CONFIG, $_OPTIONS, $_MODULES;

    $root = new SdlTag();

    $items = $root->createChild("items");
    foreach ($_CONFIG['items'] as $item) {
        $opts = array_map(function ($opt) { return $opt->name; }, $_MODULES[$item[1]]->opts);
        $conf = $items->createChild($item[1]);
        $conf->setValue($item[0]);
        foreach ($item[2] as $k=>$v) {
            if (in_array($k,$opts)) {
                $conf->setAttribute($k,$v);
            } else
                printf("Warning: No such option %s for %s\n", $k, $item[0]);
        }
    }
    $options = $root->createChild("options");
    foreach ($_OPTIONS as $key=>$option) {
        $val = $options->createChild("set");
        $val->setValue($key);
        $val->setAttribute("value", $option[0]);
    }
    $root->createChild("theme")->setValue($_CONFIG['theme']);

    file_put_contents(PHPL_CONFIG, $root->encode());
    config_read();
}


function config_option_getall() {
    global $_OPTIONS;
    $ret = [];
    foreach ($_OPTIONS as $k=>$v) {
        $ret[$k] = $v[0];
    }
    return $ret;
}
function config_option_gethelp() {
    global $_OPTIONS;
    $ret = [];
    foreach ($_OPTIONS as $k=>$v) {
        $ret[$k] = $v[2];
    }
    return $ret;
}
function config_option_get($key) {
    global $_OPTIONS;
    if (!array_key_exists($key,$_OPTIONS)) {
        throw new \Exception("No such global option {$key}");
    }
    return $_OPTIONS[$key][0];
}
function config_option_set($key,$value) {
    global $_OPTIONS;
    if (!array_key_exists($key,$_OPTIONS)) {
        throw new \Exception("No such global option {$key}");
    }
    $opt = $_OPTIONS[$key];
    if (!$opt[1]->isValid($value)) {
        throw new \InvalidArgumentException("Invalid value for option {$key}");
        return;
    }
    $cast = $opt[1]->cast($value);
    $_OPTIONS[$key][0] = $cast;
    
}

function config_item_delete($name) {
    global $_CONFIG;
    $filtered = [];
    foreach ($_CONFIG['items'] as $item) {
        if ($item[0]!=$name) {
            $filtered[] = $item;
        }
    }
    $_CONFIG['items'] = $filtered;
}

function config_item_edit($name, array $attr) {
    global $_CONFIG;
    $filtered = [];
    foreach ($_CONFIG['items'] as $item) {
        if ($item[0]==$name) {
            $item[2] = array_merge($item[2],$attr);
        }
        $filtered[] = $item;
    }
    $_CONFIG['items'] = $filtered;
}

function config_item_add($type, $name, array $position, array $attr) {
    global $_CONFIG, $_MODULES;

    if (!array_key_exists($type, $_MODULES)) {
        printf("Error: There is no panel type %s\n", $type);
    }

    list($place,$relative) = $position;
    $item = [ $name, $type, $attr ];
    switch ($place) {
        case 'first':
            foreach ($_CONFIG['items'] as $comp) {
                if ($comp[0]==$name) {
                    printf("Error: There is already a panel named %s\n", $name);
                    return;
                }
            }
            array_unshift($_CONFIG['items'], $item);
            break;
        case 'last':
            foreach ($_CONFIG['items'] as $comp) {
                if ($comp[0]==$name) {
                    printf("Error: There is already a panel named %s\n", $name);
                    return;
                }
            }
            array_push($_CONFIG['items'], $item);
            break;
        case 'before':
        case 'after':
            $ordered = [];
            foreach ($_CONFIG['items'] as $comp) {
                if ($comp[0]==$relative) {
                    if ($place=='after') $ordered[] = $comp;
                    $ordered[] = $item;
                    $item = null;
                    if ($place=='before') $ordered[] = $comp;
                } else {
                    $ordered[] = $comp;
                }
            }
            if ($item) {
                printf("Warning: Couldn't find panel item %s for relative placement\n", $relative);
                return;
            }
            $_CONFIG['items'] = $ordered;
            break;
        case 'best':
            foreach ($_CONFIG['items'] as $comp) {
                if ($comp[0]==$name) {
                    printf("Error: There is already a panel named %s\n", $name);
                    return;
                }
            }
            $_CONFIG['items'] = array_merge(
                array_slice($_CONFIG['items'],0,-1),
                [ $item ],
                array_slice($_CONFIG['items'],-1,1)
            );
            break;
    }
}

function config_item_list() {
    global $_CONFIG;
    return $_CONFIG['items'];
}

function config_item_get($name) {
    global $_CONFIG;
    $items = $_CONFIG['items'];
    foreach ($items as $item) {
        if ($item[1]==$name) return $item;
    }
    return null;
}


function scan_modules() {
    global $_CURRENT,$_SOURCE,$_MODULES;
    function module($name, $info, array $tags=[]) {
        global $_MODULES, $_CURRENT, $_SOURCE;
        $_CURRENT = $name;
        if (!is_callable("_{$name}")) {
            QUIET or printf("Warning: The module %s does not have a function _%s\n", $name, $name);
        }
        $_MODULES[$name] = (object)[
            'name' => $name,
            'info' => $info,
            'tags' => $tags,
            'opts' => [],
            'icons' => [],
            'src' => $_SOURCE
        ];
    }
    function option($name, $key, $type, $description, $default=null) {
        global $_MODULES, $_CURRENT, $_SOURCE;
        $_MODULES[$_CURRENT]->opts[] = (object)[
            'name' => $name,
            'key' => $key,
            'type' => $type,
            'descr' => $description,
            'def' => $default,
        ];
    }
    function seticon($name, $text) {
        global $_MODULES, $_CURRENT;
        $_MODULES[$_CURRENT]->icons[$name] = $text;
    }
    $modules = glob(PHPL_MODULES."/*.php");
    foreach ($modules as $module) {
        $_CURRENT = null;
        $_SOURCE = $module;
        require_once $module;
        if (!$_CURRENT) {
            QUIET or printf("Warning: The file %s does not contain any modules\n", $module);
        }
    }
    //print_r($_MODULES);
}

function demo($item) {

    global $_MODULES;

    $attr = $item[2];
    $opts = $_MODULES[$item[1]]->opts;
    $defs = [];
    $type = [];
    foreach ($opts as $opt) {
        $defs[$opt->key] = $opt->def;
        $type[$opt->key] = $opt->type;
    }
    // Coerce types
    foreach ($attr as $k=>$v) {
        if (array_key_exists($k,$type)) {
            switch ($type[$k]) {
                case 'boolean':
                    $attr[$k] = (bool)$v;
                    break;
                case 'int':
                    $attr[$k] = (int)$v;
                    break;
                case 'string':
                    $attr[$k] = (string)$v;
                    break;
                default:
                    QUIET or printf("Warning: Unknown option type %s", $type[$k]);
            }
        }
    }
    return call_user_func("_{$item[1]}", array_merge($defs,$attr));

    
}
