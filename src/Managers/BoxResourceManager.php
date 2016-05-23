<?php
namespace Box\Managers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Request;
use Box\Config\BoxConstants;

class BoxResourceManager {
    protected $config;
    protected $baseRequest;
    
    function __construct($config) {
        $this->config = $config;
        $this->baseRequest = self::createBoxRequest(BoxConstants::GET, self::createUri(BoxConstants::BOX_API_URI_STRING), $config->headers);
    }
    
    function mergeHeaders($headers, $additionalHeaders) {
        return array_merge($headers, $additionalHeaders);
    }
    
    function createUri($uri, $fields = null) {
        if (is_string($uri)) {
            $uri = new Uri($uri);
        } elseif (!($uri instanceof Uri)) {
            throw new \InvalidArgumentException(
                'URI must be a string or Psr\Http\Message\UriInterface'
            );
        }
        
        if($fields != null) {
            return self::applyFields($uri, $fields);
        }
        return $uri;
    }
    
    function applyFields($uri, $fields) {
        if($fields !== null) {
            $fieldString = implode(",", $fields);
            $uri = $uri::withQueryValue($uri, BoxConstants::FIELDS, $fieldString);
        }
        return $uri;
    } 
    
    function checkPropertySetAndNotNull($obj, $prop) {
        if(isset($obj->$prop) && is_string($obj->$prop)) {
            return $obj->$prop !== null && trim($obj->$prop) !== ''; 
        }
        return isset($obj->$prop) && $obj->$prop !== null;
    }
    
    function checkForRequiredProperties($obj, array $props) {
        foreach ($props as $prop) {
            if(!self::checkPropertySetAndNotNull($obj, $prop)) {
                return false;
            }
        }
        return true;
    }
    
    function createBoxRequest($method, $uri, $headers = [], $body = null) {
        return new Request($method, $uri, $headers, $body);
    }
    
    function alterBaseBoxRequest($request, $method = null, $uri = null, $additionalHeaders = null, $body = null) {
        if($method != null) {
            $request = $request->withMethod($method);
        }
        if($uri != null) {
            $uri = self::createUri($uri);
            $request = $request->withUri($uri);
        }
        if($additionalHeaders != null) {
            $additionalHeaders = self::mergeHeaders($this->config->headers, $additionalHeaders);
        }
        if($body != null) {
            $request = new Request($request->getMethod(), $request->getUri(), $this->config->headers, $body);
        }
        return $request;
    }
    
    function getBaseBoxRequest() {
        return $this->baseRequest;
    }
    
    function executeBoxRequestAsync($request) {
        $client = new Client();
        return $client->sendAsync($request);
    }
    
    function executeBoxRequest($request) {
        $client = new Client();
        return $client->send($request);
    }
    
    function requestTypeResolver($request, $isAsync) {
        if($isAsync) {
            return self::executeBoxRequestAsync($request);
        } else {
            return self::executeBoxRequest($request);
        }
    }
} 