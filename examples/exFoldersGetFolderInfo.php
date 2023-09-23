<?php

require_once (__DIR__ . '/../bootstrap/autoload.php');
require_once (__DIR__ . '/exHelpers.php');

use Box\Auth\BoxJWTAuth;
use Box\BoxClient;
use Box\Config\BoxConstants;

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

$res        = $boxClient->foldersManager->getFolderInfo(BoxConstants::BOX_ROOT_FOLDER_ID, null, $headers);
$folderInfo = json_decode($res->getBody());

var_dump($folderInfo);