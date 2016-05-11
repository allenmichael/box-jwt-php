<?php

require_once('./BoxJWTAuth.php');
require_once('./BoxClient.php');

$boxJwt = new BoxJWTAuth();
$boxConfig = $boxJwt->getBoxConfig();
$adminToken = $boxJwt->adminToken();
$BoxClient = new BoxClient($boxConfig, $adminToken->access_token);
$promise = $BoxClient->UsersManager->createEnterpriseUserAsync(["name" => "test123"], ["avatar_url", "name"]);
$promise->then(function($res) use ($boxJwt, $boxConfig) {
    $user = json_decode($res->getBody());
    var_dump($user->id);
    $userToken = $boxJwt->userToken($user->id);
    var_dump($userToken);
    $userClient = new BoxClient($boxConfig, $userToken->access_token);
    return $userClient->FoldersManager->getFolderItemsAsync('0');
}, function($e) {
    var_dump($e);
})
->then(function($res) {
    var_dump(json_decode($res->getBody()));
}, function($e) {
    
});