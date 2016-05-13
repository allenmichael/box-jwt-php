<?php 
namespace Box\Models\Request;

use Box\Models\Request\BoxPermissionsRequest;
use Box\Models\BoxModelConstants;
use Box\Models\Request\BoxRequestModel;
use Box\Exceptions\BoxSdkInvalidArgumentException;

class BoxSharedLinkRequest extends BoxRequestModel {
    public $access = null;
    public $password = null;
    public $unshared_at = null;
    public $permissions = null;
        
    const ACCESS = "access";
    const PASSWORD = "password";
    const UNSHARED_AT = "unshared_at";
    const PERMISSIONS = "permissions";
    const ACCESS_TYPES = [BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_OPEN, BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_COMPANY, BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_COLLABORATORS];
    
    function __construct(array $args = []) {
        if(parent::checkParamSetAndNotNull($args, self::ACCESS) && !in_array(strtoLower($args[self::ACCESS]), self::ACCESS_TYPES)) {
            throw new BoxSdkInvalidArgumentException(self::ACCESS, implode(', ', self::ACCESS_TYPES));
        }
        
        if(parent::checkParamSetAndNotNull($args, self::UNSHARED_AT) && !(parent::isValidDateTime($args[self::UNSHARED_AT]))) {
            
        }
        
        if(parent::checkParamSetAndNotNull($args, self::PERMISSIONS)) {
            $permissions = new BoxPermissionsRequest($args[self::PERMISSIONS]);
            $args[self::PERMISSIONS] = $permissions;
        }
        parent::__construct($this, $args); 
    }
}