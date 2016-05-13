<?php
namespace Box\Models\Request;

use Box\Models\Request\BoxItemRequest;
use Box\Models\Request\BoxEmailRequest;
use Box\Models\BoxModelConstants;

class BoxFolderRequest extends BoxItemRequest {
    public $folder_upload_email = null;
    public $owned_by = null;
    public $sync_state = null;
}