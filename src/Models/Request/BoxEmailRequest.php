<?php

namespace Box\Models\Request;

use Box\Models\Request\BoxRequestModel;
use Box\Models\BoxModelConstants;
use Box\Exceptions\BoxSdkInvalidArgumentException;

class BoxEmailRequest extends BoxRequestModel {
    public $access = null;
    
    const ACCESS = "access";
    const ACCESS_TYPES = [BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_OPEN, BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_COLLABORATORS];
    
    function __construct(array $args = []) {
        if(parent::checkParamSetAndNotNull($args, self::ACCESS) && !in_array(strtoLower($args[self::ACCESS]), self::ACCESS_TYPES)) {
            throw new BoxSdkInvalidArgumentException(self::ACCESS, implode(', ', self::ACCESS_TYPES));
        }
        parent::__construct($this, $args);
    }
}