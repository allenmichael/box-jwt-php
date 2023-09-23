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

$uploadedFileId   = $uploadedFileObject->entries[0]->id;
$uploadedFileName = $uploadedFileObject->entries[0]->name;

echo "Uploaded file id:   $uploadedFileId\n";
echo "Uploaded file name: $uploadedFileName\n";

unlink($filePath);

// get file info
$res        = $boxClient->filesManager->getFileInfo($uploadedFileId, null, $headers);
$folderInfo = json_decode($res->getBody());

var_dump($folderInfo);
