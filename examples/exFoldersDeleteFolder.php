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

// Create folder 1
$parentId = BoxConstants::BOX_ROOT_FOLDER_ID;
$folderName = 'Test Folder';

$folderRequest = new BoxFolderRequest(['name' => $folderName, 'parent' => ['id' => $parentId]]);
$res           = $boxClient->foldersManager->createFolder($folderRequest, null, $headers);
$folderObject  = json_decode($res->getBody());

$folder1Id = $folderObject->id;

echo "Folder ID: {$folderObject->id}\n";
echo "Folder Name: {$folderObject->name}\n";
$parent = end($folderObject->path_collection->entries);
echo "Parent ID: {$parent->id}\n";
echo "Parent Name: {$parent->name}\n";

// Create folder 2
$parentId = $folderObject->id;
$folderName = 'Test Sub Folder';

$folderRequest = new BoxFolderRequest(['name' => $folderName, 'parent' => ['id' => $parentId]]);
$res           = $boxClient->foldersManager->createFolder($folderRequest, null, $headers);
$folderObject  = json_decode($res->getBody());

echo "Folder ID: {$folderObject->id}\n";
echo "Folder Name: {$folderObject->name}\n";
$parent = end($folderObject->path_collection->entries);
echo "Parent ID: {$parent->id}\n";
echo "Parent Name: {$parent->name}\n";

// Delete folder 1
echo "Deleting $";
$deleteFolderResponse = $boxClient->foldersManager->deleteFolder($folder1Id, true, $headers);
var_dump($deleteFolderResponse->getStatusCode());