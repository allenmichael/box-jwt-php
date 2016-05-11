<?php 

require_once('./vendor/autoload.php');
require_once('./Config/BoxConfig.php');
require_once('./BoxClient.php');
require_once('./Config/BoxConstants.php');

use \Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;


class BoxJWTAuth {
    private $BoxConfig;
    private $privateKey;
    private $issuedAt;
    private $notBefore;  
    private $expire;
    private $jwtData;
    
    function __construct($BoxConfig = null, $configPath = BOX_CONSTANTS::CONFIG_PATH) {
        if($BoxConfig != null) {
            $this->BoxConfig = $BoxConfig;
        } else {
            $this->BoxConfig = new BoxConfig(new Zend\Config\Config(include $configPath));
        }
        $this->setPrivateKey();
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
            'typ' => BOX_CONSTANTS::TYPE_JWT,
            'kid' => $this->BoxConfig->jwtPublicKeyId,
            'iss' => $this->BoxConfig->clientId,
            'aud' => BOX_CONSTANTS::AUTH_CODE_JWT_ENDPOINT_STRING,
            'jti' => $this->genUuid(),
            'exp' => time() + 30,
            'sub' => $sub,
            'box_sub_type' => $boxSubType,
        );
        $jwt = JWT::encode($jwtData, $this->privateKey, BOX_CONSTANTS::ALGORITHM);
        return $jwt;
    }
    
    private function jwtPostAuth($jwt) {
        $client = new Client();
        try {
            $response = $client->request(BOX_CONSTANTS::POST, BOX_CONSTANTS::AUTH_CODE_JWT_ENDPOINT_STRING, [
                'form_params' => [
                    BOX_CONSTANTS::GRANT_TYPE => BOX_CONSTANTS::JWT_AUTHORIZATION_CODE,
                    BOX_CONSTANTS::CLIENT_ID => $this->BoxConfig->clientId,
                    BOX_CONSTANTS::CLIENT_SECRET => $this->BoxConfig->clientSecret,
                    BOX_CONSTANTS::ASSERTION => $jwt
                ]
            ]);
            return $response;
        } catch(Exception $e) {
            var_dump($e->getMessage());
        }
    }
    
    public function adminToken() {
        $jwt = $this->constructJwt($this->BoxConfig->enterpriseId, BOX_CONSTANTS::TYPE_ENTERPRISE);
        $response = $this->jwtPostAuth($jwt);
        return json_decode($response->getBody());
    }
    
    public function userToken($userId) {
        $jwt = $this->constructJwt($userId, BOX_CONSTANTS::TYPE_USER);
        $response = $this->jwtPostAuth($jwt);
        return json_decode($response->getBody());
    }
    
    public function getBoxConfig() {
        return $this->BoxConfig;
    }
}
