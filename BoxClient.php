<?php 

require_once('./vendor/autoload.php');
require_once('./Managers/BoxUsersManager.php');
require_once('./Managers/BoxFoldersManager.php');
require_once('./Config/BoxConstants.php');

class BoxClient {
    public $accessToken;
    public $headers;
    public $BoxConfig;
    
    public $UsersManager;
    public $FoldersManager;
    
    public function __construct($BoxConfig, $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->headers = [BOX_CONSTANTS::AUTH_HEADER_KEY => sprintf(BOX_CONSTANTS::V2_AUTH_STRING, $accessToken)];
        $this->BoxConfig = $BoxConfig;
        $this->initializeManagers();
    }
    
    private function initializeManagers() {
        $this->UsersManager = new BoxUsersManager($this);
        $this->FoldersManager = new BoxFoldersManager($this);
    }
}