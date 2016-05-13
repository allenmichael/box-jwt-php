<?php
namespace Box\Models;

abstract class BoxModel {
    
    function __construct($obj, $args) {
       $args = self::mergeParams($obj, $args); 
       self::resolveModelProperties($this, $args);
    }
    
    protected function isValidDateTime($date) {
        
    }
    
    protected function isJson($string) {
        if (is_string($string)) {
            json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
    
    protected function mergeParams($obj, $overwrites) {
        return array_merge(get_object_vars($obj), $overwrites);
    }
    
    protected function chooseModelPropertyValue($obj, $args, $prop) {
        return (isset($args[$prop])) ? $args[$prop] : $obj->$prop;
    }
    
    protected function resolveModelProperties($obj, $args) {
        foreach(array_keys(get_object_vars($obj)) as $prop) {
            $obj->$prop = self::chooseModelPropertyValue($obj, $args, $prop);
        }
    }
}