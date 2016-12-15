<?php

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
            if (is_int($before)) {
                $text = str_repeat(" ",$before).$text;
            } else {
                $text = $before.$text;
            }
        }
        if (($after = $this->attr['after'])) {
            if (is_int($after)) {
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

}

class Theme {
    private $rules=[];
    public function __construct(array $rules) {
        $this->rules = $rules;
    }
    public function __invoke(Panel $panel) {
        $applied = [];
        foreach ($this->rules as $rule=>$apply) {
            if ($rule=='*') {
                $applied = array_merge($applied, $apply);
                continue;
            }
            $class = $panel->get('class');
            $type = $panel->type();
            if (preg_match('/^('.$type.'){0,1}(\.'.$class.'){0,1}$/', $rule)) {
                $applied = array_merge($applied, $apply);
                continue;
            }
        }
        extract($applied, EXTR_PREFIX_ALL, "attr");
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
        $show[] = $item;
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
];
foreach ($colors as $name=>$index) define($name, $index);

function color($string) {

    switch ($string) {
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
    }
    return NONE;
}

function style($fg=NONE,$bg=NONE,$attr=NONE) {
    $sgr=[];
    if ($fg>NONE) {
        $sgr[] = ($fg>=8)?82+$fg:30+$fg;
    }
    if ($bg>NONE) {
        $sgr[] = ($bg>=8)?92+$bg:40+$bg;
    }
    if ($attr>0) {
        if (($attr & BOLD) == BOLD) $sgr[] = "1";
    }
    $pre = (count($sgr)>0)?"\[\e[".join(";",$sgr)."m\]":"";
    $post = (count($sgr)>0)?"\[\e[0m\]":"";
    return function ($text) use ($pre,$post) {
        return "{$pre}{$text}{$post}";
    };
}
