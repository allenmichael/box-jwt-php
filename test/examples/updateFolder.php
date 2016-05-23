<?php
include('./vendor/autoload.php');
use Box\Auth\BoxJWTAuth;
use Box\BoxClient;
use Box\Models\Request\BoxFolderRequest;

$boxJwt = new BoxJWTAuth();
$boxConfig = $boxJwt->getBoxConfig();
$adminToken = $boxJwt->adminToken();
$BoxClient = new BoxClient($boxConfig, $adminToken->access_token);

$user = $BoxClient->UsersManager->createEnterpriseUser(["name" => "new user"], ["avatar_url", "name"]);
$user = json_decode($user->getBody());
var_dump($user->id);
    
$userToken = $boxJwt->userToken($user->id);
var_dump($userToken);

$userClient = new BoxClient($boxConfig, $userToken->access_token);
$folder = new BoxFolderRequest(["name" => "Test Folder", "parent" => ["id" => "0"]]);
$createdFolder = $userClient->FoldersManager->createFolder($folder);
var_dump(json_decode($createdFolder->getBody()));

$createdFolder = json_decode($createdFolder->getBody());
var_dump(array($createdFolder));
$updateFolder = new BoxFolderRequest(["name" => "Updated Folder", "id" => $createdFolder->id]);
var_dump($updateFolder->id);
try {
    $updatedFolder = $userClient->FoldersManager->updateInformation($updateFolder);
    var_dump(json_decode($updatedFolder->getBody()));
} catch(Exception $e) {
    var_dump(json_decode($e->getResponse()->getBody()));
}


