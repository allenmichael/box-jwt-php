<?php

/**
 * This example illustrations how to generate an enterprise access token similar to what is documented here:
 *
 * https://developer.box.com/v2.0/docs/authentication-with-jwt#section-3-generate-an-enterprise-access-token-with-box-sdks
 */

require_once (__DIR__ . '/../bootstrap/autoload.php');
require_once (__DIR__ . '/exHelpers.php');

use Box\Auth\BoxJWTAuth;
use Box\BoxClient;

$boxJwt     = new BoxJWTAuth();
$boxConfig  = $boxJwt->getBoxConfig();
$adminToken = $boxJwt->adminToken();
$boxClient  = new BoxClient($boxConfig, $adminToken->access_token);

$retVal = $boxClient->usersManager->getMe();
$body   = json_decode($retVal->getBody());

echo "App User Login: {$body->login}\n";
echo "Access Token: {$adminToken->access_token}\n";
