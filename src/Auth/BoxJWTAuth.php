<?php 
namespace Box\Auth;

use \Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Zend\Config\Config;
use Box\BoxClient;
use Box\Config\BoxConstants;
use Box\Config\BoxConfig;

class BoxJWTAuth {
    private $BoxConfig;
    private $privateKey;
    private $issuedAt;
    private $notBefore;  
    private $expire;
    private $jwtData;
    
    function __construct($BoxConfig = null, $configPath = BoxConstants::CONFIG_PATH) {
        if($BoxConfig != null) {
            $this->BoxConfig = $BoxConfig;
        } else {
            $this->BoxConfig = new BoxConfig(new Config(include $configPath));
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
            'typ' => BoxConstants::TYPE_JWT,
            'kid' => $this->BoxConfig->jwtPublicKeyId,
            'iss' => $this->BoxConfig->clientId,
            'aud' => BoxConstants::AUTH_CODE_JWT_ENDPOINT_STRING,
            'jti' => $this->genUuid(),
            'exp' => time() + 30,
            'sub' => $sub,
            'box_sub_type' => $boxSubType,
        );
        $jwt = JWT::encode($jwtData, $this->privateKey, BoxConstants::ALGORITHM);
        return $jwt;
    }
    
    private function jwtPostAuth($jwt) {
        $client = new Client();
        try {
            $response = $client->request(BoxConstants::POST, BoxConstants::AUTH_CODE_JWT_ENDPOINT_STRING, [
                'form_params' => [
                    BoxConstants::GRANT_TYPE => BoxConstants::JWT_AUTHORIZATION_CODE,
                    BoxConstants::CLIENT_ID => $this->BoxConfig->clientId,
                    BoxConstants::CLIENT_SECRET => $this->BoxConfig->clientSecret,
                    BoxConstants::ASSERTION => $jwt
                ]
            ]);
            return $response;
        } catch(Exception $e) {
            var_dump($e->getMessage());
        }
    }
    
    public function adminToken() {
        $jwt = $this->constructJwt($this->BoxConfig->enterpriseId, BoxConstants::TYPE_ENTERPRISE);
        $response = $this->jwtPostAuth($jwt);
        return json_decode($response->getBody());
    }
    
    public function userToken($userId) {
        $jwt = $this->constructJwt($userId, BoxConstants::TYPE_USER);
        $response = $this->jwtPostAuth($jwt);
        return json_decode($response->getBody());
    }
    
    public function getBoxConfig() {
        return $this->BoxConfig;
    }
    
}