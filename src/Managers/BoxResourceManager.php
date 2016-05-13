<?php
namespace Box\Managers;

abstract class BoxResourceManager {
    protected $config;
    
    function __construct($config) {
        $this->config = $config;
    }
} 