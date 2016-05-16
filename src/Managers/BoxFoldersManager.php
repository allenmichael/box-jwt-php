<?php
namespace Box\Managers;

use Box\Config\BoxConstants;
use Box\Models\BoxModelConstants;
use Box\Managers\BoxResourceManager;
use Box\Models\Request\BoxFolderRequest;
use Box\Exceptions\BoxSdkException;
use Box\Exceptions\BoxSdkRequiredArgumentException;

class BoxFoldersManager extends BoxResourceManager {
    
    const CREATE_FOLDER_REQUIRED_PROPERTIES = [BoxModelConstants::NAME, BoxModelConstants::PARENT, BoxModelConstants::ID];
    
    function __construct($config) {
        parent::__construct($config);
    }
    
    public function getFolderItemsAsync($id, $limit = null, $offset = 0,  $fields = null, $additionalHeaders = null) {
        $uri = parent::createUri(BoxConstants::FOLDERS_ENDPOINT_STRING . $id, $fields);
        $request = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::GET, $uri, $additionalHeaders); 
        return parent::executeBoxRequestAsync($request);
    }
    
    public function createFolder($folderRequest, $fields = null, $additionalHeaders = null, $runAsync = false) {
        $uri = parent::createUri(BoxConstants::FOLDERS_ENDPOINT_STRING, $fields);
        
        if(!($folderRequest instanceof BoxFolderRequest)) {
            throw new BoxSdkException("The first argument supplied must be a BoxFolderRequest");
        }
        
    if(!parent::checkPropertySetAndNotNull($folderRequest, BoxModelConstants::NAME)) {
        throw new BoxSdkRequiredArgumentException(BoxModelConstants::NAME);
    }
    if(!parent::checkPropertySetAndNotNull($folderRequest, BoxModelConstants::PARENT)) {
        throw new BoxSdkRequiredArgumentException(BoxModelConstants::PARENT);
    } 
    if(!parent::checkPropertySetAndNotNull($folderRequest->parent, BoxModelConstants::ID)) {
        throw new BoxSdkRequiredArgumentException(BoxModelConstants::ID);
    }    
        $request = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::POST, $uri, $additionalHeaders, $folderRequest->toJson());
        return parent::requestTypeResolver($request, $runAsync);
    }
}