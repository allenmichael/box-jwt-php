<?php
namespace Box\Managers;

use Box\Config\BoxConstants;
use Box\Managers\BoxResourceManager;
use Box\Utils\Util;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

class BoxUsersManager extends BoxResourceManager {
    
    function __construct($config) {
        parent::__construct($config);
    }
    
    public function createEnterpriseUserAsync($userRequest, $fields = null) {
        $body = json_encode(['name' => $userRequest['name'], "is_platform_access_only" => true]);
        $uri = new Uri(BoxConstants::USER_ENDPOINT_STRING);
        
        $uri = Util::applyFields($uri, $fields);
        
        $request = new Request(BoxConstants::POST, $uri, $this->config->headers, $body);
        $client = new Client();
        return $client->sendAsync($request);
    }
}