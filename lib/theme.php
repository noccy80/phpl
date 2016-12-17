<?php

class Theme {

    private $rulesets = [];

    private $icons = [];

    public function compile() {

        foreach ($this->rulesets as $rule=>$ruleset) {
            printf("%s ----\n%s\n\n", $rule, $ruleset->getPropExport());
        }

    }

    public function readTheme($file) {

        $theme = file_get_contents($file);

        // Strip out all the directives $pragma, $set, $icon etc before parsing
        //echo $theme."\n\n";
        preg_match_all('/\$(pragma|icon|set|source|include)\((.+?)\)/', $theme, $directives);
        $theme = preg_replace('/\$(pragma|icon|set|source|include)\((.+?)\)/', "", $theme);

        $d_vars = [];           // vars from $set() directives
        $d_icons = [];          // icons from $icon() directives
        $d_pragma = [];         // pragmas from $pragma() directives

        $rulesets = [];

        // parse directives
        $count = count($directives[0]);
        for ($index = 0; $index < $count; $index++) {
            $directive = $directives[1][$index];
            $value = $directives[2][$index];
            switch ($directive) {
                case 'pragma':
                    $d_pragma[] = $value;
                    break;
                case 'icon':
                    list($name,$value) = explode(",",$value,2);
                    $d_icons[$name] = $value;
                    break;
                case 'source':
                    $path = dirname($file).DIRECTORY_SEPARATOR.$value;
                    if (file_exists($path)) 
                        $this->readTheme($path);
                    break;
                case 'include':
                    $path = dirname($file).DIRECTORY_SEPARATOR.$value;
                    if (!file_exists($path)) {
                        printf("Error: Could not find requested file to include %s\n", $path);
                        exit(1);
                    }
                    $this->readTheme($path);
                    break;
                case 'set':
                    list($name,$value) = explode(",",$value,2);
                    $d_vars[$name] = $value;
                    break;
                default:
                    printf("Warning: Unsupported directive \$%s\n", $directive);
            }
        }

        // Use the PHP tokenizer for parsing; # is used for comments, so
        // we substitute it with 'rgb/' first, i.e. '#fed' => 'rgb/fed'
        $theme = str_replace('#','rgb/', $theme);
        $toks = token_get_all("<?php ".$theme);
        array_shift($toks);

        $p_buf = null;          // buffer for currently parsed keyword
        $p_ruleset = null;      // name of ruleset 
        $p_property = null;     // property name 
        $p_value = null;        // property value

        // Walk and process values
        foreach ($toks as $tok) {
            if (is_array($tok)) {
                switch ($tok[0]) {
                    case T_COMMENT:
                        continue;
                    case T_WHITESPACE:
                        $p_buf.= " ";
                        break;
                    case T_STRING:
                    case T_VARIABLE:
                    case T_LNUMBER:
                    case T_DNUMBER:
                        $p_buf.=$tok[1];
                        break;
                    default:
                        $typ = token_name($tok[0]);
                        $str = $tok[1];
                        printf("%s (%s)\n", $tok[1], token_name($tok[0]));
                }
            } else {
                switch ($tok) {
                    case '{':
                        $p_ruleset = trim($p_buf);
                        $p_buf = null;
                        empty($rulesets[$p_ruleset]) && ($rulesets[$p_ruleset] = []);
                        //printf("open propset, ruleset=%s\n", $p_ruleset);
                        break;
                    case '}':
                        //printf("end propset, ruleset=%s\n", $p_ruleset);
                        $p_ruleset = null;
                        $p_buf = null;
                        break;
                    case ':':
                        $p_property = trim($p_buf);
                        $p_buf = null;
                        //printf("begin property, ruleset=%s prop=%s\n", $p_ruleset, $p_property);
                        break;
                    case ';':
                        $value = trim($p_buf);
                        if ($value && ($value[0]=='%')) {
                            $var = substr($value,1);
                            if (!array_key_exists($var,$d_vars)) {
                                printf("Error: Referencing undefined variable %s\n", $value);
                                exit(1);
                            }
                            $value = $d_vars[$var];
                        }
                        $rulesets[$p_ruleset][$p_property] = $value;
                        //printf("property: ruleset=%s prop=%s value=%s raw=%s\n", $p_ruleset, $p_property, $value, $p_buf);
                        $p_property = null;
                        $p_buf = null;
                        break;
                    default:
                        $p_buf.=$tok;
                }
            }

        }
        $this->rulesets = array_merge($this->rulesets, $rulesets);
        $this->icons = array_merge($this->icons, $d_icons);
    }

    public function getRulesets()
    {
        return $this->rulesets;
    }

    public function getIcons()
    {
        return $this->icons;
    }

}

