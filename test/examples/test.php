<?php
include('./vendor/autoload.php');
use Box\Models\Request\BoxFolderRequest;
use Box\Config\BoxConstants;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$folder = new BoxFolderRequest(["name" => "Test Folder", "parent" => ["id" => "0"]]);
var_dump($folder);

var_dump($folder->name);

