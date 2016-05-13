<?php
include('./vendor/autoload.php');
use Box\Models\Request\BoxSharedLinkRequest;
use Box\Config\BoxConstants;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$client = new Client();
$sharedLink = new BoxSharedLinkRequest(["access" => "open", "permissions" => ["can_download" => true]]);
var_dump($sharedLink);

