<?php

/**
 * This example illustrations how to generate an enterprise access token similar to what is documented here:
 *
 * https://developer.box.com/guides/authentication/jwt/user-access-tokens/
 */

require_once (__DIR__ . '/../bootstrap/autoload.php');
require_once (__DIR__ . '/exHelpers.php');

use Box\Auth\BoxJWTAuth;
use Box\Config\BoxConstants;

$boxJwt     = new BoxJWTAuth(null, __DIR__ . '/../' . BoxConstants::CONFIG_PATH);
$boxConfig  = $boxJwt->getBoxConfig();
$adminToken = $boxJwt->adminToken();

echo "Access Token: {$adminToken->access_token}\n";
