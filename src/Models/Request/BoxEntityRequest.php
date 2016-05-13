<?php
namespace Box\Models\Request;

use Box\Models\Request\BoxRequestModel;

class BoxEntityRequest extends BoxRequestModel {
    public $id;
    public $type;
    
    const ID = "id";
    const TYPE = "type";
    
    function __construct(array $args = []) {
        parent::__construct($this, $args);
    }
}