<?php
namespace Box\Managers;

use Box\Config\BoxConstants;
use Box\Managers\BoxResourceManager;
use Box\Utils\Util;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

class BoxFoldersManager extends BoxResourceManager {
    
    function __construct($config) {
        parent::__construct($config);
    }
    
    public function getFolderItemsAsync($id, $limit = null, $offset = 0,  $fields = null) {
        $uri = new Uri(BoxConstants::FOLDERS_ENDPOINT_STRING . $id);
        $uri = Util::applyFields($uri, $fields);
        
        $request = new Request(BoxConstants::GET, $uri, $this->config->headers); 
        $client = new Client();
        return $client->sendAsync($request);
    }
    
    public function createAsync($folderRequest, $fields = null) {
        $uri = new Uri(BoxConstants::FOLDERS_ENDPOINT_STRING);
        $uri = parent::applyFields($uri, $fields);
    }
}