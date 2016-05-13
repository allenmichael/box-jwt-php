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
    
    function createBoxRequest($method, $uri, $headers = [], $body = null) {
        return new Request($method, $uri, $headers, $body);
    }
    
    function alterBaseBoxRequest($request, $method = null, $uri = null, $headers = null, $body = null) {
        if($method != null) {
            $request = $request->withMethod($method);
        }
        if($uri != null) {
            $uri = self::createUri($uri);
            $request = $request->withUri($uri);
        }
        if($headers != null) {
            $headers = self::mergeHeaders($this->config->headers, $headers);
        }
        if($body != null) {
            $request = new Request($request->getMethod(), $request->getUri(), $headers, $body);
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
} 