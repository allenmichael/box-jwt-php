<?php
namespace Box\Models\Request;

class BoxRequestEntity {
    public $id;
    public $type;
    
    function __construct($id, $type) {
        $this->id = $id;
        $this->type = $type;
    }
}