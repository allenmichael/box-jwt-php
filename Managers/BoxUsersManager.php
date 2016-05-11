<?php

require_once('./Config/BoxConstants.php');
require_once('./Managers/BoxResourceManager.php');

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

class BoxUsersManager extends BoxResourceManager {
    
    function __construct($config) {
        parent::__construct($config);
    }
    
    public function createEnterpriseUserAsync($userRequest, $fields = null) {
        $body = json_encode(['name' => $userRequest['name'], "is_platform_access_only" => true]);
        $uri = new Uri(BOX_CONSTANTS::USER_ENDPOINT_STRING);
        
        $uri = parent::applyFields($uri, $fields);
        
        $request = new Request('POST', $uri, $this->config->headers, $body);
        $client = new Client();
        return $client->sendAsync($request);
    }
}