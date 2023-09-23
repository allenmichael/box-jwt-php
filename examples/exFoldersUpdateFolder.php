<?php

require_once (__DIR__ . '/../bootstrap/autoload.php');
require_once (__DIR__ . '/exHelpers.php');

use Box\Auth\BoxJWTAuth;
use Box\BoxClient;
use Box\Config\BoxConstants;
use Box\Models\Request\BoxFolderRequest;

$userLogin = '<box-user-login-email>';

$boxJwt     = new BoxJWTAuth(null, __DIR__ . '/../' . BoxConstants::CONFIG_PATH);
$boxConfig  = $boxJwt->getBoxConfig();
$adminToken = $boxJwt->adminToken();
$boxClient  = new BoxClient($boxConfig, $adminToken->access_token);

$res   = $boxClient->usersManager->getEnterpriseUsers(null, $userLogin);
$users = json_decode($res->getBody());

if (!$users->total_count) {
    die("No users found for $userLogin.\n");
}

$user    = $users->entries[0];
$headers = [BoxConstants::HEADER_KEY_AS_USER => $user->id];

// Create folder
$parentId   = BoxConstants::BOX_ROOT_FOLDER_ID;
$folderName = 'Test Folder';

$folderRequest = new BoxFolderRequest(['name' => $folderName, 'parent' => ['id' => $parentId]]);
$res           = $boxClient->foldersManager->createFolder($folderRequest, null, $headers);
$createdFolderObject  = json_decode($res->getBody());

echo "Folder ID: {$createdFolderObject->id}\n";
echo "Folder Name: {$createdFolderObject->name}\n";
$parent = end($createdFolderObject->path_collection->entries);
echo "Parent ID: {$parent->id}\n";
echo "Parent Name: {$parent->name}\n";

// Update folder - Rename
$newFolderName = 'Test Folder New Name';

$folderRequest = new BoxFolderRequest(['name' => $newFolderName, "id" => $createdFolderObject->id]);

$res                 = $boxClient->foldersManager->updateFolder($folderRequest, null, $headers);
$updatedFolderObject = json_decode($res->getBody());

echo "Folder ID: {$updatedFolderObject->id}\n";
echo "Folder Name: {$updatedFolderObject->name}\n";
$parent = end($updatedFolderObject->path_collection->entries);
echo "Parent ID: {$parent->id}\n";
echo "Parent Name: {$parent->name}\n";
