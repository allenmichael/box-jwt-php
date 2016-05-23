<?php
namespace Box\Models\Request;

use Box\Models\Request\BoxItemRequest;
use Box\Models\Request\BoxEmailRequest;
use Box\Models\Request\BoxEntityRequest;
use Box\Models\BoxModelConstants;

class BoxFolderRequest extends BoxItemRequest {
    public $folder_upload_email = null;
    public $owned_by = null;
    public $sync_state = null;
    
    const FOLDER_UPLOAD_EMAIL = "folder_upload_email";
    const OWNED_BY = "owned_by";
    const SYNC_STATE = "sync_state";
    const SYNC_STATE_VALUES = [BoxModelConstants::BOX_SYNC_STATE_TYPE_SYNCED, BoxModelConstants::BOX_SYNC_STATE_TYPE_NOT_SYNCED, BoxModelConstants::BOX_SYNC_STATE_TYPE_PARTIALLY_SYNCED];
    
    function __construct(array $args = []) {
        if(parent::checkParamSetAndNotNull($args, self::FOLDER_UPLOAD_EMAIL)) {
            $args[self::FOLDER_UPLOAD_EMAIL] = new BoxEmailRequest($args[self::FOLDER_UPLOAD_EMAIL]);
        }
        
        if(parent::checkParamSetAndNotNull($args, self::OWNED_BY)) {
            $args[self::OWNED_BY] = new BoxEntityRequest($args[self::OWNED_BY]);
        }
        
        if(parent::checkParamSetAndNotNull($args, self::SYNC_STATE) && !in_array(strtoLower($args[self::SYNC_STATE]), self::SYNC_STATE_VALUES)) {
            throw new BoxSdkInvalidArgumentException(self::ACCESS, implode(', ', self::SYNC_STATE_VALUES));
        }
        
        parent::__construct($args);
    }
}