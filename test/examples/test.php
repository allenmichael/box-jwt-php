<?php
include('./vendor/autoload.php');
use Box\Models\Request\BoxSharedLinkRequest;
use GuzzleHttp\Client;

$client = new Client();
$sharedLink = new BoxSharedLinkRequest(["access" => "open"]);
var_dump($sharedLink);