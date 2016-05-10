<?php 

require_once('./vendor/autoload.php');
require_once('./Managers/BoxUsersManager.php');

class BoxClient {
    public $accessToken;
    public $headers;
    const BASE_URL = "https://api.box.com/2.0/";
    
    public $UsersManager;
    
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->headers = ['Authorization' => 'Bearer ' . $accessToken];
        $this->initializeManagers();
    }
    
    private function initializeManagers() {
        $this->UsersManager = new BoxUsersManager($this);
    }
}