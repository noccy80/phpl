<?php

class VarType
{

    private static $TYPES = [];
    
    private $type;
    
    private $def;

    private $conf;
    
    public static function bindType($type, callable $valid, callable $caster)
    {
        $cn = ucwords($type)."Var";
        $def =  "class {$cn} extends VarType {". 
                "function __construct(\$def=null,\$conf=null) { ". 
                "parent::__construct(".var_export($type,true).",\$def,\$conf);".
                "}}";
                
        eval($def);
        self::$TYPES[$type] = [ $valid, $caster ];
    }

    public static function __callStatic($method, $args)
    {
        $def=(count($args)>0)?$args[0]:null;
        $conf=(count($args)>1)?$args[1]:null;
        if (!array_key_exists($method,self::$TYPES)) {
            throw new \Exception("No such var type: {$method}");
        }
        return new VarType($method,$def,$conf);
    }
    
    public function __construct($type,$def,$conf)
    {
        $this->type = $type;
        $this->def = $def;
        $this->conf = $conf;
    }

    public function isValid($value)
    {
        $func = self::$TYPES[$this->type][0];
        if ($func instanceof \Closure) {
            return call_user_func($func, $value, $this->conf);
        } else {
            return call_user_func($func, $value);
        }
    }

    /**
     * Returns the string value cast to the variable type. Throws
     * an error on invalid data.
     *
     */
    public function cast($value)
    {
        $func = self::$TYPES[$this->type][1];
        try {
            $ret = call_user_func($func, $value);
            return $ret;
        } catch (\Exception $e) {
            return $this->def;
        }
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getDefault()
    {
        return $this->def;
    }
    
}

VarType::bindType("int", "is_numeric", "intval");
VarType::bindType("float", "is_numeric", "floatval");
VarType::bindType("string", "is_string", "strval");

VarType::bindType("bool",
    function($v) { return in_array($v,[1,0,"yes","no","true","false","on","off",true,false]); },
    function($v) { return in_array($v,[1,"yes","true","on",true],true); }
);

VarType::bindType("enum",
    function($v,$c) { return in_array($v,(array)$c); },
    function($v) { return $v; }
);
