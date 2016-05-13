<?php 
namespace Box\Models\Request;
class BoxPermissionsRequest {
    public $can_download = null;
  
    const CAN_DOWNLOAD = "can_download";
    
    function __construct(array $args) {
        
    }
}