<?php

namespace Box;

use Box\Managers\BoxResourceManager;
use Box\Managers\BoxUsersManager;
use Box\Managers\BoxFoldersManager;
use Box\Managers\BoxFilesManager;
use Box\Config\BoxConstants;

class BoxClient
{
    /**
     * @var \stdClass
     */
    public $accessToken;

    /**
     * @var string[]
     */
    public $headers;

    /**
     * @var \Box\Config\BoxConfig
     */
    public $BoxConfig;

    /**
     * @var \Box\Managers\BoxResourceManager
     */
    public $resourceManager;

    /**
     * @var \Box\Managers\BoxUsersManager
     */
    public $usersManager;

    /**
     * @var \Box\Managers\BoxFoldersManager
     */
    public $foldersManager;

    /**
     * @var \Box\Managers\BoxFilesManager
     */
    public $filesManager;

    public function __construct($BoxConfig, $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->headers     = [BoxConstants::HEADER_KEY_AUTH => sprintf(BoxConstants::HEADER_VAL_V2_AUTH_STRING, $accessToken)];
        $this->BoxConfig   = $BoxConfig;
        $this->initializeManagers();
    }

    private function initializeManagers()
    {
        $this->resourceManager = new BoxResourceManager($this);
        $this->usersManager    = new BoxUsersManager($this);
        $this->foldersManager  = new BoxFoldersManager($this);
        $this->filesManager    = new BoxFilesManager($this);
    }
}