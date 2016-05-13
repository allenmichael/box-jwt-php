<?php
namespace Box\Managers;

use Box\Config\BoxConstants;
use Box\Managers\BoxResourceManager;

class BoxUsersManager extends BoxResourceManager {
    
    function __construct($config) {
        parent::__construct($config);
    }
    
    public function createEnterpriseUserAsync($userRequest, $fields = null) {
        $body = json_encode(['name' => $userRequest['name'], "is_platform_access_only" => true]);
        $uri = parent::createUri(BoxConstants::USER_ENDPOINT_STRING, $fields);
        $request = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::POST, $uri, $this->config->headers, $body);
        return parent::executeBoxRequestAsync($request);
    }
}