<?php 
namespace Box\Models\Request;

use Box\Models\Request;
use Box\Utils\Util;
use Box\Models\BoxModelConstants;

class BoxSharedLinkRequest {
    public $access = [self::ACCESS => null];
    public $password = [self::PASSWORD => null];
    public $unsharedAt = [self::UNSHARED_AT => null];
    public $permissions = [self::PERMISSIONS => null];
        
    const ACCESS = "access";
    const PASSWORD = "password";
    const UNSHARED_AT = "unshared_at";
    const PERMISSIONS = "permissions";
    const ACCESS_TYPES = [BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_OPEN, BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_COMPANY, BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_COLLABORATORS];
    
    function __construct(array $args = []) {
        $args = Util::mergeParams($this, $args);
        
        if($args[self::ACCESS] != null && !in_array(strtoLower($args[self::ACCESS]), self::ACCESS_TYPES)) {
            throw new InvalidArgumentException("Access Type not supported. Please use one of the following: " . implode(', ', self::ACCESS_TYPES));
        }
        
        if($args[self::UNSHARED_AT] != null && !(Util::isValidDateTime($args[self::UNSHARED_AT]))) {
            
        }
        
        $this->access = Util::resolveModelProperties($args, self::ACCESS, $this->access);
        $this->password = (isset($args[self::PASSWORD])) ? [self::PASSWORD => $args[self::PASSWORD]] : $this->password; 
        $this->unsharedAt = (isset($args[self::UNSHARED_AT])) ? [self::UNSHARED_AT => $args[self::UNSHARED_AT]] : $this->unsharedAt; 
    }
}