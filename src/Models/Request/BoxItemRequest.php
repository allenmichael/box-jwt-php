<?php
namespace Box\Models\Request;

use Box\Models\Request\BoxRequestEntity;

class BoxItemRequest extends BoxRequestEntity {
    public $parent;
    public $name;
    public $description;
    public $sharedLink;
    
    function __construct($args) {
        
    }
}