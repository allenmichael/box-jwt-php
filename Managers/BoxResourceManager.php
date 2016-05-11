<?php
abstract class BoxResourceManager {
    protected $config;
    
    function __construct($config) {
        $this->config = $config;
    }
    
    protected function applyFields($uri, $fields) {
        if($fields !== null) {
            $fieldString = implode(",", $fields);
            $uri = $uri::withQueryValue($uri, BOX_CONSTANTS::FIELDS, $fieldString);
        }
        return $uri;
    } 
} 