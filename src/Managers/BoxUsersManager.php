<?php

namespace Box\Managers;

use Box\Config\BoxConstants;
use Box\Managers\BoxResourceManager;

/**
 * Class BoxUsersManager
 *
 * @package Box\Managers
 */
class BoxUsersManager extends BoxResourceManager
{

    /**
     * BoxUsersManager constructor.
     *
     * @param \Box\BoxClient $boxClient BoxClient instance.
     */
    function __construct($boxClient)
    {
        parent::__construct($boxClient);
    }

    /**
     * Create user.
     *
     * If you're creating an app user, 'is_platform_access_only' must be set to true
     * in the $userRequest array.
     *
     * https://developer.box.com/reference#create-an-enterprise-user
     * https://developer.box.com/reference#create-app-user
     *
     * @param stirng[] $userRequest Array of user request
     * @param string[] $fields      Array of fields to return in response.
     * @param bool     $runAsync    Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createEnterpriseUser($userRequest, $fields = null, $runAsync = false)
    {
        $body      = json_encode(['name' => $userRequest['name'], BoxConstants::IS_PLATFORM_ACCESS_ONLY => true]);
        $urlParams = $this->keepNonEmptyParams([BoxConstants::QUERY_PARAM_FIELDS => $fields]);
        $uri       = parent::createUri(BoxConstants::USER_ENDPOINT_STRING, $urlParams);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::POST, $uri, $this->boxClient->headers, $body);
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Get enterprise user.  Only available to admin accounts or service accounts.
     *
     * https://developer.box.com/reference#get-all-users-in-an-enterprise
     *
     * @param string   $userType          The type of user to search for. One of all, external or managed.
     *                                    The default is managed.
     * @param null     $filterTerm        Only return users whose name or login matches the filter_term.
     *                                    See notes below for details on the matching.
     * @param int      $offset            The offset of the item at which to begin the response.
     * @param int      $limit             The maximum number of items to return.If none is provided,
     *                                    the default is 100 and the maximum is 1,000.
     * @param string[] $fields            Array of fields to return in response.
     * @param bool     $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getEnterpriseUsers($userType = null, $filterTerm = null, $offset = null, $limit = null, $fields = null, $runAsync = false)
    {
        $urlParams = $this->keepNonEmptyParams([
            BoxConstants::QUERY_PARAM_USER_TYPE   => $userType,
            BoxConstants::QUERY_PARAM_FILTER_TERM => $filterTerm,
            BoxConstants::QUERY_PARAM_OFFSET      => $offset,
            BoxConstants::QUERY_PARAM_LIMIT       => $limit,
            BoxConstants::QUERY_PARAM_FIELDS      => $fields,
        ]);
        $uri       = parent::createUri(BoxConstants::USER_ENDPOINT_STRING, $urlParams);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::GET, $uri, $this->boxClient->headers);
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Get current user.
     *
     * @param bool $runAsync Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMe($runAsync = false)
    {
        $uri     = parent::createUri(BoxConstants::USER_ME_ENDPOINT_STRING);
        $request = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::GET, $uri, $this->boxClient->headers);
        return parent::requestTypeResolver($request, [], $runAsync);
    }

}