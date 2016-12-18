<?php
/*
 *  This file is used to support the generation of prompts. It provides the main
 *  functions used for theming etc.
 *
 *
 **************************************************************************************/

class Panel {
    private $text;
    private $attr;
    public function __construct($text, array $attr=[], $type) {
        $this->text = $text;
        $this->type = $type;
        $this->attr = array_merge([
            'class'=>null,
            'style'=>null,
            'before'=>1,
            'after'=>1,
        ],$attr);
    }
    public function __toString() {
        $text = $this->text;
        if (!$text) return "";
        if (($before = $this->attr['before'])) {
            if (is_numeric($before)) {
                $text = str_repeat(" ",$before).$text;
            } else {
                $text = $before.$text;
            }
        }
        if (($after = $this->attr['after'])) {
            if (is_numeric($after)) {
                $text = $text.str_repeat(" ",$after);
            } else {
                $text = $text.$after;
            }
        }
        if (($style = $this->attr['style'])) {
            return $style($text);
        }
        return $text;
    }
    public function set($k,$v) {
        $this->attr[$k] = $v;
    }
    public function get($k) {
        return array_key_exists($k,$this->attr)?$this->attr[$k]:null;
    }
    public function type() {
        return $this->type;
    }
}

class Style {
    const BOLD = 0x01;
    const ITALIC = 0x02;
    const UNDERLINE = 0x04;
    const REVERSE = 0x08;
    protected $color;
    protected $background;
    protected $style;
}

class Theme {
    private $rules=[];
    public function __construct(array $rules) {
        $this->rules = $rules;
    }
    public function __invoke(Panel $panel) {
        $applied = ['background'=>'none','color'=>'none'];
        foreach ($this->rules as $rule=>$apply) {
            if ($rule=='*') {
                $applied = array_merge($applied, $apply);
                continue;
            }
            $class = $panel->get('class');
            $status = $panel->get('status');
            $type = $panel->type();
            if (preg_match('/^('.$type.'){0,1}(\.'.$class.'){0,1}(\:'.$status.'){0,1}$/', $rule)) {
                $applied = array_merge($applied, $apply);
                continue;
            }
        }
        extract($applied, EXTR_PREFIX_ALL, "attr");
        if (array_key_exists('pad-before', $applied)) $panel->set('before', $applied['pad-before']);
        if (array_key_exists('pad-after', $applied)) $panel->set('after', $applied['pad-after']);
        
        $fg = color($attr_color); $bg = color($attr_background);
        return style($fg,$bg);
    }
}

function theme(Panel &$panel) {
    global $_THEME;
    $panel->set('style',$_THEME($panel));
}

function panel($text, array $attr=[], $type) {
    return new Panel($text, $attr, $type);
}

function generate(...$items) {
    $show = [];
    foreach ($items as $item) {
        if (!($item instanceof Panel))
            continue;
        theme($item);
        $show[] = (string)$item;
    }
    $generated = join("",$show);
    echo $generated;
}

$colors = [
    "NONE"      => -1,
    "BLACK"     => 0,
    "RED"       => 1,
    "GREEN"     => 2,
    "YELLOW"    => 3,
    "BLUE"      => 4,
    "MAGENTA"   => 5,
    "CYAN"      => 6,
    "WHITE"     => 7,
    "GRAY"      => 8,
    "BR_RED"    => 9,
    "BR_GREEN"  => 10,
    "BR_YELLOW" => 11,
    "BR_BLUE"   => 12,
    "BR_MAGENTA"=> 13,
    "BR_CYAN"   => 14,
    "BR_WHITE"  => 15,
    "BOLD"      => 1,
    "COLOR256"  => 256,
    "COLOR24M"  => 1024,
];
foreach ($colors as $name=>$index) define($name, $index);

function color($string) {
    if (is_int($string)) return $string;
    switch ($string) {
        case null:
        case 'none': return NONE;
        case 'black': return BLACK;
        case 'red': return RED;
        case 'green': return GREEN;
        case 'yellow': return YELLOW;
        case 'blue': return BLUE;
        case 'magenta': return MAGENTA;
        case 'cyan': return CYAN;
        case 'white': return WHITE;
        case 'gray': return GRAY;
        case 'bright-red': return BR_RED;
        case 'bright-green': return BR_GREEN;
        case 'bright-yellow': return BR_YELLOW;
        case 'bright-blue': return BR_BLUE;
        case 'bright-magenta': return BR_MAGENTA;
        case 'bright-cyan': return BR_CYAN;
        case 'bright-white': return BR_WHITE;
        default:
            if ($string[0]=='#') {
                $r = hexdec(substr($string,1,2));
                $g = hexdec(substr($string,3,2));
                $b = hexdec(substr($string,5,2));
                if (defined("PRAGMA_TRUECOLOR")) {
                    $a = [$r,$g,$b]; 
                } else {
                    $a = COLOR256 + 16 + 36*floor($r/42) + 6*floor($g/42) + floor($b/42);
                }
                return $a;
            }
    }
    return NONE;
}

function style($fg=NONE,$bg=NONE,$attr=NONE) {
    $sgr=[];
    if (is_array($fg)) {
        $sgr[] = 38; $sgr[] = 2;
        $sgr[] = $fg[0];
        $sgr[] = $fg[1];
        $sgr[] = $fg[2];
    } elseif ($fg==NONE) {
    } elseif ($fg>=COLOR256) {
        $sgr[] = 38; $sgr[] = 5;
        $sgr[] = $fg - COLOR256;
    } else {
        $sgr[] = ($fg>=8)?82+$fg:30+$fg;
    }
    if (is_array($bg)) {
        $sgr[] = 48; $sgr[] = 2;
        $sgr[] = $bg[0];
        $sgr[] = $bg[1];
        $sgr[] = $bg[2];
    } elseif ($bg==NONE) {
    } elseif ($bg>=COLOR256) {
        $sgr[] = 48; $sgr[] = 5;
        $sgr[] = $bg - COLOR256;
    } else {
        $sgr[] = ($bg>=8)?92+$bg:40+$bg;
    }
    if ($attr>0) {
        if (($attr & BOLD) == BOLD) $sgr[] = "1";
    }
    if (defined('PROMPT_RAW') && PROMPT_RAW) {
        $pre = (count($sgr)>0)?"\e[".join(";",$sgr)."m":"";
        $post = (count($sgr)>0)?"\e[0m":"";
    } else {
        $pre = (count($sgr)>0)?"\[\e[".join(";",$sgr)."m\]":"";
        $post = (count($sgr)>0)?"\[\e[0m\]":"";
    }
    return function ($text) use ($pre,$post) {
        return "{$pre}{$text}{$post}";
    };
}

define("IS_TTY",(strpos(exec("tty"),"/dev/tty")===0));