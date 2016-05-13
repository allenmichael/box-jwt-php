<?php
include('./Models/Request/BoxSharedLinkRequest.php');
require_once('./BoxJWTAuth.php');
require_once('./BoxClient.php');

use Box\Models\Request\BoxSharedLinkRequest;

$boxJwt = new BoxJWTAuth();
$boxConfig = $boxJwt->getBoxConfig();
$adminToken = $boxJwt->adminToken();
$BoxClient = new BoxClient($boxConfig, $adminToken->access_token);

// var_dump($BoxClient->createBaseBoxRequest("GET", "https://api.box.com/2.0/"));

$sharedLink = new BoxSharedLinkRequest(["access" => "open"]);
var_dump($sharedLink);