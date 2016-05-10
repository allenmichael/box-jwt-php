<?php

require_once('./BoxJWTAuth.php');
require_once('./BoxClient.php');

$boxJwt = new BoxJWTAuth();
$adminToken = $boxJwt->adminToken();
$BoxClient = new BoxClient($adminToken->access_token);
$promise = $BoxClient->UsersManager->createEnterpriseUserAsync(["name" => "test123"]);
$promise->then(function($res) {
    var_dump(json_decode($res->getBody())); 
}, function($e) {
    var_dump($e);
});