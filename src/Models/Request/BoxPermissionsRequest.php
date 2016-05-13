<?php 
namespace Box\Models\Request;
class BoxPermissionsRequest {
    public $canDownload = [self::CAN_DOWNLOAD => null];
  
    const CAN_DOWNLOAD = "can_download";
    
    function __construct(array $args) {
        
    }
}