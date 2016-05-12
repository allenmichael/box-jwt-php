<?php

require_once('./Models/Request/BoxPermissionsRequest.php');
require_once('./Models/BoxModelConstants.php');

class BoxSharedLinkRequest {
    public $access = [self::ACCESS => null];
    public $password = [self::PASSWORD => null];
    public $unsharedAt = [self::UNSHARED_AT => null];
    public $permissions = [self::PERMISSIONS => null];
        
    const ACCESS = "access";
    const PASSWORD = "password";
    const UNSHARED_AT = "unshared_at";
    const PERMISSIONS = "permissions";
    const ACCESS_TYPES = [BOX_MODEL_CONSTANTS::BOX_SHARED_LINK_ACCESS_TYPE_OPEN, BOX_MODEL_CONSTANTS::BOX_SHARED_LINK_ACCESS_TYPE_COMPANY, BOX_MODEL_CONSTANTS::BOX_SHARED_LINK_ACCESS_TYPE_COLLABORATORS];
    
    function __construct(array $args) {
        $defaults = array_merge($this->access, $this->password, $this->unsharedAt, $this->permissions);
        echo $defaults;
        $args = array_merge($defaults, $args);
        if($args[self::ACCESS] != null && !in_array(strtoLower($args[self::ACCESS]), self::ACCESS_TYPES)) {
            throw new Exception("Access Type not supported. Please use one of the following: " . implode(', ', self::ACCESS_TYPES));
        }
    }
}