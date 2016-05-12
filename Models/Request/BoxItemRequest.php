<?php

require_once('./Models/Request/BoxRequestEntity.php');

class BoxItemRequest extends BoxRequestEntity {
    public $parent;
    public $name;
    public $description;
    public $sharedLink;
    
    function __construct($args) {
        
    }
}