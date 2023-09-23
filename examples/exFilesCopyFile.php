<?php

require_once (__DIR__ . '/../bootstrap/autoload.php');
require_once (__DIR__ . '/exHelpers.php');

use Box\Auth\BoxJWTAuth;
use Box\BoxClient;
use Box\Config\BoxConstants;
use Box\Models\Request\BoxFileRequest;

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

// upload file
$parentId = BoxConstants::BOX_ROOT_FOLDER_ID;
$filePath = createTempFile();
$fileName = basename($filePath);

$fileRequest        = new BoxFileRequest(['name' => $fileName, 'parent' => ['id' => $parentId]]);
$res                = $boxClient->filesManager->uploadFile($fileRequest, $filePath, $headers);
$uploadedFileObject = json_decode($res->getBody());
$uploadedFileObject = $uploadedFileObject->entries[0];

echo "Uploaded file id:   $uploadedFileObject->id\n";
echo "Uploaded file name: $uploadedFileObject->name\n";
$uploadedFileParent = end($uploadedFileObject->path_collection->entries);
echo "Parent ID: {$uploadedFileParent->id}\n";
echo "Parent Name: {$uploadedFileParent->name}\n";

unlink($filePath);

// copy file
$newFileName = 'Test Copied File.tmp';

$res              = $boxClient->filesManager->copyFile($uploadedFileObject->id, $parentId, $newFileName, null, $headers);
$copiedFileObject = json_decode($res->getBody());

echo "Folder ID: {$copiedFileObject->id}\n";
echo "Folder Name: {$copiedFileObject->name}\n";
$copiedFileParent = end($copiedFileObject->path_collection->entries);
echo "Parent ID: {$copiedFileParent->id}\n";
echo "Parent Name: {$copiedFileParent->name}\n";
