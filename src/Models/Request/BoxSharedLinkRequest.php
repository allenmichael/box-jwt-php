<?php 
namespace Box\Models\Request;

use Box\Models\Request;
use Box\Models\BoxModelUtils;
use Box\Models\BoxModelConstants;
use Box\Models\BoxModel;

class BoxSharedLinkRequest extends BoxModel {
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
        if($args[self::ACCESS] != null && !in_array(strtoLower($args[self::ACCESS]), self::ACCESS_TYPES)) {
            throw new \InvalidArgumentException("Access Type not supported. Please use one of the following: " . implode(', ', self::ACCESS_TYPES));
        }
        
        if($args[self::UNSHARED_AT] != null && !(parent::isValidDateTime($args[self::UNSHARED_AT]))) {
            
        }
        parent::__construct($this, $args); 
    }
}