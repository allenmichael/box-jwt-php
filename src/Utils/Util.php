<?php
namespace Box\Utils;

use Box\Config\BoxConstants;

abstract class Util {
    static function isValidDateTime($date) {
        
    }
    
    static function applyFields($uri, $fields) {
        if($fields !== null) {
            $fieldString = implode(",", $fields);
            $uri = $uri::withQueryValue($uri, BoxConstants::FIELDS, $fieldString);
        }
        return $uri;
    } 
    
    static function isJson($string) {
        if (is_string($string)) {
            json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
    
    static function mergeParams($obj, $overwrites) {
        $defaults = array_reduce((array) get_object_vars($obj), function($prev, $curr) {
            return array_merge($prev, (array) $curr);
        }, $initial = []);

        return array_merge($defaults, $overwrites);
    }
    
    static function resolveModelProperties($args, $keyword, $currentProperty) {
        return (isset($args[$keyword])) ? [$keyword => $args[$keyword]] : $currentProperty;
    }
}