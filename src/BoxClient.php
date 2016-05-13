<?php 

namespace Box;

use Box\Managers\BoxResourceManager;
use Box\Managers\BoxUsersManager;
use Box\Managers\BoxFoldersManager;
use Box\Config\BoxConstants;

class BoxClient {
    public $accessToken;
    public $headers;
    public $BoxConfig;
    
    public $ResourceManager;
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
        $this->ResourceManager = new BoxResourceManager($this);
        $this->UsersManager = new BoxUsersManager($this);
        $this->FoldersManager = new BoxFoldersManager($this);
    }
}