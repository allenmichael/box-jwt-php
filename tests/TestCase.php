<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Box\Auth\BoxJWTAuth;
use Box\BoxClient;
use Box\Config\BoxConstants;

/**
 * Class TestCase
 *
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    const TEST_FOLDER_NAME = 'TEST_FOLDER';

    /** @var \Box\Config\BoxConfig */
    protected $boxConfig;

    /** @var string */
    protected $authToken;

    /** @var \Box\BoxClient */
    protected $boxClient;

    /** @var \stdClass */
    protected $asUser;

    /** @var string */
    protected $testFolderId;

    public function setUp(): void
    {
        parent::setUp();
        $this->getEnterpriseAccessToken();
        $this->getAsUser();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Gets the full path to the configuration file.
     *
     * @return bool|string
     */
    protected function getConfigPath()
    {
        $config = getenv('CONFIG');
        return realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $config);
    }

    /**
     * Gets the App User/Service Account enterprise access token.
     *
     * https://developer.box.com/guides/authentication/jwt/jwt-setup/
     *
     * @return string
     */
    protected function getEnterpriseAccessToken()
    {
        $configPath      = $this->getConfigPath();
        $boxJwt          = new BoxJWTAuth(null, $configPath);
        $this->boxConfig = $boxJwt->getBoxConfig();
        $this->authToken = $boxJwt->adminToken()->access_token;
        $this->boxClient = new BoxClient($this->boxConfig, $this->authToken);

        return $this->authToken;
    }

    /**
     * Email login of Box user that the App User/Service Account will
     * impersonate using the 'As-User' HTTP request header:
     *
     *     As-User: <box-user-id>
     *
     * The email of the Box user is configured in the environment
     * variable 'AS_USER_EMAIL'.
     *
     * https://developer.box.com/guides/authentication/jwt/as-user/
     *
     * @return array|false|string
     */
    protected function asUserEmail()
    {
        return getenv('AS_USER_EMAIL');
    }

    /**
     * Get Box user that the service account will impersonate with 'As-User'
     * HTTP request header.
     *
     * The email of the Box user is configured in the environment
     * variable 'AS_USER_EMAIL'.
     *
     * @param bool $forceReload
     *
     * @return null|\stdClass
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getAsUser($forceReload = false)
    {
        // Only get asUser once unless forced
        if (!$forceReload && !is_null($this->asUser)) {
            return $this->asUser;
        }

        $res   = $this->boxClient->usersManager->getEnterpriseUsers(null, $this->asUserEmail());
        $users = json_decode($res->getBody());

        if (!$users->total_count) {
            return null;
        }

        $this->asUser = $users->entries[0];

        return $this->asUser;
    }

    /**
     * Returns the 'As-User' HTTP header.
     *
     * @return array
     */
    protected function getAsUserHeader()
    {
        return [BoxConstants::HEADER_KEY_AS_USER => $this->asUser->id];
    }
}