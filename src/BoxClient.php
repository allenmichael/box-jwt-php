<?php 

namespace Box;

use Box\Managers\BoxUsersManager;
use Box\Managers\BoxFoldersManager;
use Box\Config\BoxConstants;
use Box\Utils\Util;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Promise\Promise;

class BoxClient {
    public $accessToken;
    public $headers;
    public $BoxConfig;
    
    public $UsersManager;
    public $FoldersManager;
    
    function __construct($BoxConfig, $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->headers = [BoxConstants::AUTH_HEADER_KEY => sprintf(BoxConstants::V2_AUTH_STRING, $accessToken)];
        $this->BoxConfig = $BoxConfig;
        $this->initializeManagers();
    }
    
    private function initializeManagers() {
        $this->UsersManager = new BoxUsersManager($this);
        $this->FoldersManager = new BoxFoldersManager($this);
    }
    
    function createBaseBoxRequest($method, $uri, array $additionalHeaders = [], $body = null, $fields = null) {
        $headers = array_merge($this->headers, $additionalHeaders);
        
        if (is_string($uri)) {
            $uri = new Uri($uri);
        } elseif (!($uri instanceof UriInterface)) {
            throw new \InvalidArgumentException(
                'URI must be a string or Psr\Http\Message\UriInterface'
            );
        }
        
        $uri = Util::applyFields($uri, $fields);
        
        return new Request($method, $uri, $headers, $body);
    }
    
    function executeBoxRequestAsync($request) {
        $client = new Client();
        return $client->sendAsync($request);
    }
    
}