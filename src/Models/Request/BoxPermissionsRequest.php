<?php 
namespace Box\Models\Request;

use Box\Models\Request\BoxRequestModel;
use Box\Exceptions\BoxSdkBadArgumentTypeException;

class BoxPermissionsRequest extends BoxRequestModel {
    public $can_download = null;
  
    const CAN_DOWNLOAD = "can_download";
    
    function __construct(array $args = []) {
        if(parent::checkParamSetAndNotNull($args, self::CAN_DOWNLOAD) && gettype($args[self::CAN_DOWNLOAD]) != "boolean") {
            throw new BoxSdkBadArgumentTypeException(self::CAN_DOWNLOAD, "boolean");
        }
        parent::__construct($this, $args);
    }
}