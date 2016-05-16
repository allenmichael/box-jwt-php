<?php
include('./vendor/autoload.php');
use Box\Auth\BoxJWTAuth;
use Box\BoxClient;
use Box\Models\Request\BoxFolderRequest;

$boxJwt = new BoxJWTAuth();
$boxConfig = $boxJwt->getBoxConfig();
$adminToken = $boxJwt->adminToken();
$BoxClient = new BoxClient($boxConfig, $adminToken->access_token);

$user = $BoxClient->UsersManager->createEnterpriseUser(["name" => "test123"], ["avatar_url", "name"]);
$user = json_decode($user->getBody());
var_dump($user->id);
    
$userToken = $boxJwt->userToken($user->id);
var_dump($userToken);

$userClient = new BoxClient($boxConfig, $userToken->access_token);
$folder = new BoxFolderRequest(["name" => "Test Folder", "parent" => ["id" => "0"]]);
$createdFolder = $userClient->FoldersManager->createFolder($folder);
var_dump(json_decode($createdFolder->getBody()));