<?php

require_once('./Config/BoxConstants.php');
require_once('./Managers/BoxResourceManager.php');

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

class BoxFoldersManager extends BoxResourceManager {
    
    function __construct($config) {
        parent::__construct($config);
    }
    
    public function getFolderItemsAsync($id, $limit = null, $offset = 0,  $fields = null) {
        $uri = new Uri(BOX_CONSTANTS::FOLDERS_ENDPOINT_STRING . $id);
        $uri = parent::applyFields($uri, $fields);
        
        $request = new Request(BOX_CONSTANTS::GET, $uri, $this->config->headers); 
        $client = new Client();
        return $client->sendAsync($request);
    }
    
    public function createAsync($folderRequest, $fields = null) {
        $uri = new Uri(BOX_CONSTANTS::FOLDERS_ENDPOINT_STRING);
        $uri = parent::applyFields($uri, $fields);
    }
}