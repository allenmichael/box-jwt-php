<?php
namespace Box\Models\Request;

use Box\Models\Request\BoxRequestModel;
use Box\Models\Request\BoxEntityRequest;
use Box\Models\BoxModelConstants;
use Box\Exceptions\BoxSdkException;

class BoxItemRequest extends BoxRequestModel {
    public $parent = null;
    public $name = null;
    public $description = null;
    public $shared_link = null;
    
    const PARENT = "parent";
    const NAME = "name";
    const DESCRIPTION = "description";
    const SHARED_LINK = "shared_link";
    
    function __construct(array $args = []) {
        if(parent::checkParamSetAndNotNull($args, self::PARENT)) {
            $parent = new BoxEntityRequest($args[self::PARENT]);
            $args[self::PARENT] = $parent;
        }
        
        if(parent::checkParamSetAndNotNull($args, self::NAME) && strlen($args[self::NAME]) > BoxModelConstants::BOX_FOLDER_AND_FILE_NAME_CHARACTER_LIMIT) {
            throw new BoxSdkException(sprintf("%s property must be shorter than %s charaters", self::NAME, BoxModelConstants::BOX_FOLDER_AND_FILE_NAME_CHARACTER_LIMIT));
        }
        
        parent::__construct($this, $args);
    }
    
}