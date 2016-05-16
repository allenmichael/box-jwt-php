<?php
namespace Box\Models\Request;

use Box\Models\BoxModel;

abstract class BoxRequestModel extends BoxModel {   
    function __construct($obj, $args) {
        $args = parent::mergeParams($obj, $args); 
        parent::resolveModelProperties($this, $args);
    }
    
    function toJson() {
        return json_encode($this);
    }
}