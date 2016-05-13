<?php
namespace Box\Managers;

use Box\Config\BoxConstants;
use Box\Managers\BoxResourceManager;

class BoxFoldersManager extends BoxResourceManager {
    
    function __construct($config) {
        parent::__construct($config);
    }
    
    public function getFolderItemsAsync($id, $limit = null, $offset = 0,  $fields = null, $additionalHeaders = null) {
        $uri = parent::createUri(BoxConstants::FOLDERS_ENDPOINT_STRING . $id, $fields);
        $request = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::GET, $uri, $additionalHeaders); 
        return parent::executeBoxRequestAsync($request);
    }
    
    public function createAsync($folderRequest, $fields = null) {
        $uri = new Uri(BoxConstants::FOLDERS_ENDPOINT_STRING);
        $uri = parent::applyFields($uri, $fields);
    }
}