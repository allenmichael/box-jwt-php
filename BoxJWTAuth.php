<?php 

require_once('./vendor/autoload.php');
require_once('./BoxConfig.php');
require_once('./BoxClient.php');

use \Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;


class BoxJWTAuth {
    private $BoxConfig;
    private $privateKey;
    private $jwtToken;
    private $issuedAt;
    private $notBefore;  
    private $expire;
    private $jwtData;
    const AUTH_URL = 'https://api.box.com/oauth2/token';
    const GRANT_TYPE = 'urn:ietf:params:oauth:grant-type:jwt-bearer';
    const ENTERPRISE_TYPE = "enterprise";
    const USER_TYPE = "user";
    
    function __construct($BoxConfig = null, $configPath = 'box.config.php') {
        if($BoxConfig != null) {
            $this->BoxConfig = new BoxConfig($BoxConfig);
        } else {
            $this->BoxConfig = new BoxConfig(new Zend\Config\Config(include $configPath));
        }
        $this->setPrivateKey();
        $this->jwtToken = $this->genUuid();
    }
    
    private function setPrivateKey() {
        $privateKeyResource = openssl_get_privatekey(sprintf("file://%s", $this->BoxConfig->jwtPrivateKey), $this->BoxConfig->jwtPrivateKeyPassword);
        openssl_pkey_export($privateKeyResource, $privateKey);
        $this->privateKey = $privateKey;
    }

    private function genUuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    private function constructJwt($sub, $boxSubType) {
        $jwtData = array(
            'typ' => 'JWT',
            'kid' => $this->BoxConfig->jwtPublicKeyId,
            'iss' => $this->BoxConfig->clientId,
            'aud' => self::AUTH_URL,
            'jti' => $this->jwtToken,
            'exp' => time() + 30,
            'sub' => $sub,
            'box_sub_type' => $boxSubType,
        );
        $jwt = JWT::encode($jwtData, $this->privateKey, "RS256");
        return $jwt;
    }
    
    private function jwtPostAuth($jwt) {
        $client = new Client();

        $response = $client->request('POST', 'https://api.box.com/oauth2/token', [
            'form_params' => [
                'grant_type' => self::GRANT_TYPE,
                'client_id' => $this->BoxConfig->clientId,
                'client_secret' => $this->BoxConfig->clientSecret,
                'assertion' => $jwt
            ]
        ]);
        
        return $response;
    }
    
    public function adminToken() {
        $jwt = $this->constructJwt($this->BoxConfig->enterpriseId, self::ENTERPRISE_TYPE);
        $response = $this->jwtPostAuth($jwt);
        return json_decode($response->getBody());
    }
    
    public function userToken($userId) {
        $jwt = $this->constructJwt($userId, self::USER_TYPE);
        $response = $this->jwtPostAuth($jwt);
        return $response->access_token;
    }
}
