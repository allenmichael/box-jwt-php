<?php

require_once(__DIR__ . '/../bootstrap/autoload.php');
require_once(__DIR__ . '/exHelpers.php');

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
$filePath = createTempImage();
$fileName = basename($filePath);

$fileRequest        = new BoxFileRequest(['name' => $fileName, 'parent' => ['id' => $parentId]]);
$res                = $boxClient->filesManager->uploadFile($fileRequest, $filePath, $headers);
$uploadedFileObject = json_decode($res->getBody());

$uploadedFileId   = $uploadedFileObject->entries[0]->id;
$uploadedFileName = $uploadedFileObject->entries[0]->name;

echo "Uploaded file id:   $uploadedFileId\n";
echo "Uploaded file name: $uploadedFileName\n";

unlink($filePath);

// get file thumbnail
$res        = $boxClient->filesManager->getThumbnail($uploadedFileId, 'png', null, null, 128, 128, $headers);
$statusCode = $res->getStatusCode();
echo "HTTP STATUS: " . $statusCode . "\n";

if ($statusCode == 202) {
    $retryAfter = $res->getHeader('Retry-After')[0];
    $location   = $res->getHeader('Location')[0];

    echo "Retry-After: $retryAfter s\n";
    echo "Placeholder Location: $location\n";
    echo "Waiting for $retryAfter s...\n";
    sleep($retryAfter);
    echo "Retrying...\n";

    $res        = $boxClient->filesManager->getThumbnail($uploadedFileId, 'png', null, null, 128, 128, $headers);
    $statusCode = $res->getStatusCode();
    echo "HTTP STATUS: " . $statusCode . "\n";
    echo "Writing thumbnail to " . realpath('thumbnail.png') . "\n";
    file_put_contents('thumbnail.png', $res->getBody());

    $imgSizeInfo = getimagesize('thumbnail.png');
    echo "Image size: " . $imgSizeInfo[3] . "\n";
    echo "Mime type: " . $imgSizeInfo['mime'] . "\n";
}
