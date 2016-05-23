<?php
include('./vendor/autoload.php');
use Box\Models\Request\BoxFolderRequest;
use Box\Config\BoxConstants;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$folder = new BoxFolderRequest(["name" => "Test Folder", "parent" => ["id" => "0"], "id" => "123"]);
var_dump($folder);
$folder->name = "Changed!";

var_dump($folder->name);

