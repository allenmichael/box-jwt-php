<?php

namespace Tests\Feature;

use Tests\TestCase;
use Box\Config\BoxConstants;
use Box\Models\Request\BoxFolderRequest;

class FoldersTest extends TestCase
{
    use TestHelpers;

    public function setUp()
    {
        parent::setUp();
        $this->setupTestFolder();
    }

    public function tearDown()
    {
        $this->tearDownTestFolder();
        parent::tearDown();
    }

    /** @test */
    public function app_user_as_user_can_get_folder_info()
    {
        $headers    = $this->getAsUserHeader();
        $parentId   = BoxConstants::BOX_ROOT_FOLDER_ID;
        $res        = $this->boxClient->foldersManager->getFolderInfo($parentId, null, $headers);
        $folderInfo = json_decode($res->getBody());

        $this->assertEquals('folder', $folderInfo->type);
        $this->assertEquals($parentId, $folderInfo->id);
    }

    /** @test */
    public function app_user_as_user_can_create_folder()
    {
        $headers = $this->getAsUserHeader();

        // create folder
        $folderName = $this->generateTestFolderName();
        $parentId   = $this->testFolderId;
        $this->createFolderTestHelper($folderName, $parentId, $headers);
    }

    /** @test */
    public function app_user_as_user_can_delete_folder()
    {
        $headers = $this->getAsUserHeader();

        // create folder
        $folderName          = $this->generateTestFolderName();
        $parentId            = $this->testFolderId;
        $createdFolderObject = $this->createFolderTestHelper($folderName, $parentId, $headers);

        // delete folder
        $this->deleteFolder($createdFolderObject->id, null, $headers);
    }

    /** @test */
    public function app_user_as_user_can_copy_folder()
    {
        $headers = $this->getAsUserHeader();

        // create folder
        $folderName          = $this->generateTestFolderName();
        $parentId            = $this->testFolderId;
        $createdFolderObject = $this->createFolderTestHelper($folderName, $parentId, $headers);
        $createdFolderId     = $createdFolderObject->id;

        // Copy folder
        $newFolderName = $this->generateTestFolderName();

        $res                = $this->boxClient->foldersManager->copyFolder($createdFolderId, $parentId, $newFolderName, null, $headers);
        $copiedFolderObject = json_decode($res->getBody());
        $copiedFolderParent = end($copiedFolderObject->path_collection->entries);

//        echo "Copied Folder ID:   {$copiedFolderObject->id}\n";
//        echo "Copied Folder Name: {$copiedFolderObject->name}\n";
//        echo "Copied Parent ID:   {$copiedFolderParent->id}\n";

        $this->assertNotEmpty($copiedFolderObject->id);
        $this->assertEquals($newFolderName, $copiedFolderObject->name);
        $this->assertEquals($parentId, $copiedFolderParent->id);
    }

    /** @test */
    public function app_user_as_user_can_update_folder()
    {
        $headers = $this->getAsUserHeader();

        // create folder
        $folderName          = $this->generateTestFolderName();
        $parentId            = $this->testFolderId;
        $createdFolderObject = $this->createFolderTestHelper($folderName, $parentId, $headers);
        $createdFolderId     = $createdFolderObject->id;

        // update (rename) folder
        $folderNewName = $this->generateTestFolderName();

        $folderRequest       = new BoxFolderRequest(['name' => $folderNewName, "id" => $createdFolderId]);
        $res                 = $this->boxClient->foldersManager->updateFolder($folderRequest, null, $headers);
        $updatedFolderObject = json_decode($res->getBody());
        $updatedFolderParent = end($updatedFolderObject->path_collection->entries);

//        echo "Copied Folder ID:   {$updatedFolderObject->id}\n";
//        echo "Copied Folder Name: {$updatedFolderObject->name}\n";
//        echo "Copied Parent ID:   {$updatedFolderParent->id}\n";

        $this->assertNotEmpty($updatedFolderObject->id);
        $this->assertEquals($folderNewName, $updatedFolderObject->name);
        $this->assertEquals($parentId, $updatedFolderParent->id);
    }

    /** @test */
    public function app_user_as_user_can_get_folder_items()
    {
        $headers = $this->getAsUserHeader();

        // create folder
        $folderName   = $this->generateTestFolderName();
        $parentId     = $this->testFolderId;
        $folderObject = $this->createFolderTestHelper($folderName, $parentId, $headers);

        // check for created folder
        $res         = $this->boxClient->foldersManager->getFolderItems($parentId, null, null, null, $headers);
        $folderItems = json_decode($res->getBody());

        $filteredFolderItems = array_filter($folderItems->entries, function ($item) use ($folderName, $folderObject) {
            $condition1 = property_exists($item, 'id') && $item->id == $folderObject->id;
            $condition2 = property_exists($item, 'name') && $item->name == $folderName;
            return $condition1 && $condition2;
        });

        $this->assertCount(1, $filteredFolderItems);
    }

    /**
     * Create folder test helper.
     *
     * @param string $folderName Name of folder to create.
     * @param string $parentId   Parent ID of where folder will be created.
     * @param array  $headers    HTTP request headers to be applied.
     *
     * @return mixed
     * @throws \Exception
     */
    protected function createFolderTestHelper($folderName, $parentId, $headers = null)
    {
        $createdFolderObject = $this->createFolder($folderName, $parentId, $headers);

        $this->assertEquals('folder', $createdFolderObject->type);

        $createdFolderParent   = end($createdFolderObject->path_collection->entries);

//        echo "Folder ID:   $createdFolderObject->id\n";
//        echo "Folder Name: $createdFolderObject->name\n";
//        echo "Parent ID:   $createdFolderParent->id\n";

        $this->assertNotEmpty($createdFolderObject->id);
        $this->assertEquals($folderName, $createdFolderObject->name);
        $this->assertEquals($parentId, $createdFolderParent->id);

        return $createdFolderObject;
    }

    /**
     * Generator a test folder name
     *
     * @return string
     */
    protected function generateTestFolderName()
    {
        return self::TEST_FOLDER_NAME . '_' . bin2hex(openssl_random_pseudo_bytes(8));
    }
}