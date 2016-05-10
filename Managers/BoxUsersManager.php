<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class BoxUsersManager {
    private $client;
    const USER_URL = "users";
    
    function __construct($client) {
        $this->client = $client;
    }
    
    public function createEnterpriseUserAsync($userRequest, $fields = null) {
        $body = json_encode(['name' => $userRequest['name'], "is_platform_access_only" => true]);
        $request = new Request('POST', $this->client::BASE_URL . self::USER_URL, $this->client->headers, $body);
        $client = new Client();
        return $client->sendAsync($request);
    }
}