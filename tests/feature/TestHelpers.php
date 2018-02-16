<?php

namespace Tests\Feature;

use Box\Models\Request\BoxFolderRequest;
use GuzzleHttp\Psr7\Response;

trait TestHelpers
{
    protected function setupTestFolder()
    {
        $headers    = $this->getAsUserHeader();
        $parentId   = '0';

        $folderObject = $this->createFolder(self::TEST_FOLDER_NAME, $parentId, $headers);

        $this->assertEquals('folder', $folderObject->type);

        $parent = end($folderObject->path_collection->entries);
        $this->testFolderId = $folderObject->id;

//        $this->assertNotEmpty($folderObject->id);
        $this->assertEquals(self::TEST_FOLDER_NAME, $folderObject->name);
        $this->assertEquals($parentId, $parent->id);
//        $this->assertEquals('All Files', $parent->name);
    }

    protected function tearDownTestFolder()
    {
        $headers = $this->getAsUserHeader();
        $res = $this->boxClient->foldersManager->deleteFolder($this->testFolderId, true, $headers);

        $this->assertInstanceOf(Response::class, $res);
        $this->assertEquals(204, $res->getStatusCode());
    }

    protected function createFolder($folderName, $parentId, $headers = null)
    {
        $folderRequest = new BoxFolderRequest(['name' => $folderName, "parent" => ['id' => $parentId]]);
        $res           = $this->boxClient->foldersManager->createFolder($folderRequest, null, $headers);

        $this->assertInstanceOf(Response::class, $res);
        $this->assertEquals(201, $res->getStatusCode());
        return json_decode($res->getBody());
    }

    protected function deleteFolder($folderId, $recursive = null, $headers = null)
    {
        $res = $this->boxClient->foldersManager->deleteFolder($folderId, $recursive, $headers);

        $this->assertInstanceOf(Response::class, $res);
        $this->assertEquals(204, $res->getStatusCode());
    }
}