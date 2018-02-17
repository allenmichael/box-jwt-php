<?php

require_once (__DIR__ . '/../bootstrap/autoload.php');
require_once (__DIR__ . '/exHelpers.php');

use Box\Auth\BoxJWTAuth;
use Box\BoxClient;
use Box\Config\BoxConstants;
use Box\Models\Request\BoxFolderRequest;

$userLogin = '<box-user-login-email>';

$boxJwt     = new BoxJWTAuth();
$boxConfig  = $boxJwt->getBoxConfig();
$adminToken = $boxJwt->adminToken();
$boxClient  = new BoxClient($boxConfig, $adminToken->access_token);

$res   = $boxClient->usersManager->getEnterpriseUsers(null, $userLogin);
$users = json_decode($res->getBody());

if (!$users->total_count) {
    return "No users found for $userLogin.\n";
}

$user    = $users->entries[0];
$headers = [BoxConstants::HEADER_KEY_AS_USER => $user->id];

// Create folder
$parentId = '0';
$folderName = 'Test Folder';

$folderRequest = new BoxFolderRequest(['name' => $folderName, 'parent' => ['id' => $parentId]]);
$res           = $boxClient->foldersManager->createFolder($folderRequest, null, $headers);
$folderObject  = json_decode($res->getBody());

echo "Folder ID: {$folderObject->id}\n";
echo "Folder Name: {$folderObject->name}\n";
$parent = end($folderObject->path_collection->entries);
echo "Parent ID: {$parent->id}\n";
echo "Parent Name: {$parent->name}\n";

// Create subfolder
//$parentId = $folderObject->id;
//$folderName = 'Test SubFolder';
//
//$folderRequest = new BoxFolderRequest(['name' => $folderName, 'parent' => ['id' => $parentId]]);
//$res           = $boxClient->FoldersManager->createFolder($folderRequest, null, $headers);
//$folderObject  = json_decode($res->getBody());
//
//echo "Folder ID: {$folderObject->id}\n";
//echo "Folder Name: {$folderObject->name}\n";
//$parent = end($folderObject->path_collection->entries);
//echo "Parent ID: {$parent->id}\n";
//echo "Parent Name: {$parent->name}\n";
