<?php

class GlobalEnv {
    
    static private $global = array();

    static public function set($name, $value) {
        self::$global[$name] = $value;
    }

    static public function get($name, $default=null) {
        if (array_key_exists($name, self::$global)){
            return self::$global[$name];
        }
        else {
            return $default;
        }
    }

}