<?php

namespace Box\Managers;

use Box\Config\BoxConstants;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class BoxResourceManager
 *
 * @package Box\Managers
 */
class BoxResourceManager
{
    /** @var \Box\BoxClient $boxClient */
    protected $boxClient;

    /** @var \GuzzleHttp\Psr7\Request $baseRequest */
    protected $baseRequest;

    /**
     * BoxResourceManager constructor.
     *
     * @param \Box\BoxClient $boxClient BoxClient instance.
     */
    function __construct($boxClient)
    {
        $this->boxClient   = $boxClient;
        $this->baseRequest = self::createBoxRequest(BoxConstants::GET, self::createUri(BoxConstants::BOX_API_URI_STRING), $boxClient->headers);
    }

    /**
     * Merge HTTP headders.
     *
     * @param array $headers           HTTP header array.
     * @param array $additionalHeaders Additional HTTP header array.
     *
     * @return array
     */
    function mergeHeaders($headers, $additionalHeaders)
    {
        return array_merge($headers, $additionalHeaders);
    }

    /**
     * @param string|\Psr\Http\Message\UriInterface $uri URI string or instance.
     * @param array                                 $params
     *
     * @return \GuzzleHttp\Psr7\Uri
     */
    function createUri($uri, $params = null)
    {
        if (is_string($uri)) {
            $uri = new Uri($uri);
        } elseif (!($uri instanceof UriInterface)) {
            throw new \InvalidArgumentException(
                'URI must be a string or ' . UriInterface::class
            );
        }

        if ($params != null) {
            foreach ($params as $paramKey => $paramValue) {
                if ($paramKey == BoxConstants::QUERY_PARAM_FIELDS) {
                    $uri = self::applyFieldsQueryParam($uri, $params);
                } else {
                    $uri = $uri::withQueryValue($uri, $paramKey, $paramValue);
                }
            }
        }

        return $uri;
    }

    /**
     * Apply 'fields' query parameter to URI.
     *
     * @param \GuzzleHttp\Psr7\Uri $uri    URI instance
     * @param string[]             $fields Array of fields.
     *
     * @return mixed
     */
    protected function applyFieldsQueryParam($uri, $fields)
    {
        if ($fields !== null) {
            $fieldString = implode(',', $fields);
            $uri         = $uri::withQueryValue($uri, BoxConstants::QUERY_PARAM_FIELDS, $fieldString);
        }
        return $uri;
    }

    /**
     * Create new Box (Guzzle) request.
     *
     * @param string              $method            HTTP method (verb), e.g. GET, POST
     * @param string|UriInterface $uri               URI string
     * @param array               $additionalHeaders Additional HTTP header array
     * @param string              $body              HTTP body
     *
     * @return \GuzzleHttp\Psr7\Request
     */
    function createBoxRequest($method, $uri, $headers = [], $body = null)
    {
        return new Request($method, $uri, $headers, $body);
    }

    /**
     * Alter the base Box (Guzzle) request.
     *
     * @param \GuzzleHttp\Psr7\Request                                               $request           Box (Guzzle)
     *                                                                                                  request
     *                                                                                                  instance.
     * @param string                                                                 $method            HTTP method
     *                                                                                                  (verb), e.g.
     *                                                                                                  GET, POST
     * @param string|UriInterface                                                    $uri               URI string or
     *                                                                                                  instance.
     * @param array                                                                  $additionalHeaders Additional HTTP
     *                                                                                                  header array.
     * @param resource|string|int|float|bool|StreamInterface|callable|\Iterator|null $body              HTTP body.
     *
     * @return \GuzzleHttp\Psr7\Request
     */
    function alterBaseBoxRequest($request, $method = null, $uri = null, $additionalHeaders = null, $body = null)
    {
        if ($method !== null) {
            $request = $request->withMethod($method);
        }

        if ($uri !== null) {
            $uri     = self::createUri($uri);
            $request = $request->withUri($uri);
        }

        if ($additionalHeaders !== null) {
            $additionalHeaders = self::mergeHeaders($this->boxClient->headers, $additionalHeaders);
            foreach ($additionalHeaders as $header => $value) {
                $request = $request->withHeader($header, $value);
            }
        }

        if ($body !== null) {
            $body    = Utils::streamFor($body);
            $request = $request->withBody($body);
        }
        return $request;
    }

    /**
     * Get Base Box (Guzzle) request instance.
     *
     * @return \GuzzleHttp\Psr7\Request
     */
    function getBaseBoxRequest()
    {
        return $this->baseRequest;
    }

    /**
     * Execute Box request asynchronously.
     *
     * @param \GuzzleHttp\Psr7\Request $request Box (Guzzle) request instance.
     * @param array                    $options Guzzle request options.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    function executeBoxRequestAsync($request, $options = [])
    {
        $client = new Client();
        return $client->sendAsync($request, $options);
    }

    /**
     * Execute Box request synchronously.
     *
     * @param \GuzzleHttp\Psr7\Request $request Box (Guzzle) request instance.
     * @param array                    $options Guzzle request options.
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function executeBoxRequest($request, $options = [])
    {
        $client = new Client();
        return $client->send($request, $options);
    }

    /**
     * Resolve Box request synchronously or asynchronously.
     *
     * @param \GuzzleHttp\Psr7\Request $request Box (Guzzle) request instance.
     * @param array                    $options Guzzle request options.
     * @param bool                     $isAsync Sync/async flag.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function requestTypeResolver($request, $options = [], $isAsync = false)
    {
        if ($isAsync) {
            return self::executeBoxRequestAsync($request, $options);
        } else {
            return self::executeBoxRequest($request, $options);
        }
    }

    /**
     * Filter out and keep only non-emtpy params.
     *
     * @param array $params Parameter array.
     *
     * @return array|null
     */
    protected function keepNonEmptyParams($params)
    {
        $params = array_filter($params, function ($v) {
            return !empty($v);
        });

        return empty($params) ? null : $params;
    }
}