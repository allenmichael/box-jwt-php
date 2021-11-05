<?php

namespace Tests\Feature;

use Tests\TestCase;
use Box\Models\Request\BoxFileRequest;

class FilesTest extends TestCase
{
    use TestHelpers;

    protected $tempFileList = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->setupTestFolder();
    }

    public function tearDown(): void
    {
        $this->removeTempFiles();
        $this->tearDownTestFolder();
        parent::tearDown();
    }

    /** @test */
    public function app_user_as_user_can_up_load_file()
    {
        $headers = $this->getAsUserHeader();

        // upload file
        $filePath = $this->createTempFile();
        $fileName = basename($filePath);
        $parentId = $this->testFolderId;
        $this->uploadFileTestHelper($fileName, $filePath, $parentId, $headers);
    }

    /** @test */
    public function app_user_as_user_can_get_file_info()
    {
        $headers = $this->getAsUserHeader();

        // upload file
        $filePath           = $this->createTempFile();
        $fileName           = basename($filePath);
        $parentId           = $this->testFolderId;
        $uploadedFileObject = $this->uploadFileTestHelper($fileName, $filePath, $parentId, $headers);

        // get file info
        $res      = $this->boxClient->filesManager->getFileInfo($uploadedFileObject->id, null, $headers);
        $fileInfo = json_decode($res->getBody());

        $this->assertEquals('file', $fileInfo->type);
        $this->assertEquals($uploadedFileObject->id, $fileInfo->id);
    }

    /** @test */
    public function app_user_as_user_can_copy_file()
    {
        $headers = $this->getAsUserHeader();

        // upload file
        $filePath           = $this->createTempFile();
        $fileName           = basename($filePath);
        $parentId           = $this->testFolderId;
        $uploadedFileObject = $this->uploadFileTestHelper($fileName, $filePath, $parentId, $headers);

        // copy file
        $newFileName = basename($this->createTempFile(false));

        $res              = $this->boxClient->filesManager->copyFile($uploadedFileObject->id, $parentId, $newFileName, null, $headers);
        $copiedFileObject = json_decode($res->getBody());
        $copiedFileParent = end($copiedFileObject->path_collection->entries);

//        echo "Folder ID: {$copiedFileObject->id}\n";
//        echo "Folder Name: {$copiedFileObject->name}\n";
//        echo "Parent ID: {$copiedFileParent->id}\n";
//        echo "Parent Name: {$copiedFileParent->name}\n";

        $this->assertEquals($newFileName, $copiedFileObject->name);
        $this->assertEquals($parentId, $copiedFileParent->id);
    }

    /** @test */
    public function app_user_as_user_can_download_file()
    {
        $headers = $this->getAsUserHeader();

        // upload file
        $filePath           = $this->createTempFile();
        $fileName           = basename($filePath);
        $parentId           = $this->testFolderId;
        $uploadedFileObject = $this->uploadFileTestHelper($fileName, $filePath, $parentId, $headers);

        // download file
        $downloadFilePath = $this->createTempFile(false);

        $res = $this->boxClient->filesManager->downloadFile($uploadedFileObject->id, $downloadFilePath, null, $headers);

        $this->assertEquals(200, $res->getStatusCode());

        $actualContent     = file_get_contents($filePath);
        $downloadedContent = file_get_contents($downloadFilePath);

        $this->assertEquals($actualContent, $downloadedContent);
    }

    /** @test */
    public function app_user_as_user_can_delete_file()
    {
        $headers = $this->getAsUserHeader();

        // upload file
        $filePath           = $this->createTempFile();
        $fileName           = basename($filePath);
        $parentId           = $this->testFolderId;
        $uploadedFileObject = $this->uploadFileTestHelper($fileName, $filePath, $parentId, $headers);

        // delete file
        $res = $this->boxClient->filesManager->deleteFile($uploadedFileObject->id, $headers);

        $this->assertEquals(204, $res->getStatusCode());
    }

    /** @test */
    public function app_user_as_user_can_get_file_thumbnail()
    {
        $headers = $this->getAsUserHeader();

        // upload file
        $filePath           = $this->createTempImage();
        $fileName           = basename($filePath);
        $parentId           = $this->testFolderId;
        $uploadedFileObject = $this->uploadFileTestHelper($fileName, $filePath, $parentId, $headers);

        // get file thumbnail
        $res = $this->boxClient->filesManager->getThumbnail($uploadedFileObject->id, 'png', null, null, 128, 128, $headers);

        // retry once if not available
        if ($res->getStatusCode() == 202) {
            $retryAfter = $res->getHeader('Retry-After')[0];

            sleep($retryAfter);

            $res = $this->boxClient->filesManager->getThumbnail($uploadedFileObject->id, 'png', null, null, 128, 128, $headers);
        }

        $this->assertEquals(200, $res->getStatusCode());

        $tempImageName = $this->createTempFileName('png');
        file_put_contents($tempImageName, $res->getBody());

        $imgSizeInfo = getimagesize($tempImageName);

        $this->assertEquals('width="128" height="128"', $imgSizeInfo[3]);
        $this->assertEquals('image/png', $imgSizeInfo['mime']);
    }

    /** @test */
    public function app_user_as_user_can_get_file_embedded_link()
    {
        $headers = $this->getAsUserHeader();

        // upload file
        $filePath           = $this->createTempImage();
        $fileName           = basename($filePath);
        $parentId           = $this->testFolderId;
        $uploadedFileObject = $this->uploadFileTestHelper($fileName, $filePath, $parentId, $headers);

        // get file thumbnail
        $res = $this->boxClient->filesManager->getFileEmbedLink($uploadedFileObject->id, $headers);

        $this->assertEquals(200, $res->getStatusCode());

        $embeddedLinkObject = json_decode($res->getBody());
        $this->assertIsString(filter_var($embeddedLinkObject->expiring_embed_link->url, FILTER_VALIDATE_URL));
    }

    protected function getFileThumbnail($fileId)
    {
        $res        = $this->boxClient->filesManager->getThumbnail($fileId, 'png', null, null, 128, 128, $headers);
        $folderInfo = json_decode($res->getBody());
        $statusCode = $res->getStatusCode();
    }

    /**
     * @param string                                            $fileName          Destination name of file to upload.
     * @param string|resource|\Psr\Http\Message\StreamInterface $file              File path, file resource or
     *                                                                             StreamInterface instance.
     * @param string                                            $parentId          Parent ID of where folder will be
     *                                                                             created.
     * @param null                                              $headers
     *
     * @return mixed
     * @throws \Box\Exceptions\BoxSdkException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function uploadFileTestHelper($fileName, $file, $parentId, $headers = null)
    {
        $fileRequest        = new BoxFileRequest(['name' => $fileName, 'parent' => ['id' => $parentId]]);
        $res                = $this->boxClient->filesManager->uploadFile($fileRequest, $file, $headers);
        $uploadedFileObject = json_decode($res->getBody());

        $this->assertNotEmpty($uploadedFileObject->entries);

        $uploadedFileObject = $uploadedFileObject->entries[0];

        $this->assertEquals('file', $uploadedFileObject->type);
        $this->assertNotEmpty($uploadedFileObject->id);
        $this->assertEquals($fileName, $uploadedFileObject->name);

        return $uploadedFileObject;
    }

    /**
     * Create a temp file and register it.
     *
     * If file exists on exit, it'll be automatically removed.
     *
     * @param bool $autoPopulate If true, temp file will be auto populated.
     *
     * @return bool|string
     */
    protected function createTempFile($autoPopulate = true)
    {
        $tempFileName         = tempnam(sys_get_temp_dir(), 'box');
        $this->tempFileList[] = $tempFileName;

        if ($autoPopulate) {
            file_put_contents($tempFileName, 'AUTO_GENERATED_CONTENT: ' . bin2hex(openssl_random_pseudo_bytes(8)));
        }

        return $tempFileName;
    }

    /**
     * Create a temp PNG file.
     *
     * If file exists on exit, it'll be automatically removed.
     *
     * @return string
     * @throws \Exception
     */
    protected function createTempImage()
    {
        // Create image
        $png = imagecreatetruecolor(800, 600);
        imagesavealpha($png, true);

        $trans_colour = imagecolorallocatealpha($png, 0, 0, 0, 127);
        imagefill($png, 0, 0, $trans_colour);

        $red = imagecolorallocate($png, 255, 0, 0);
        imagefilledellipse($png, 400, 300, 400, 300, $red);

        // Create temp filename
        $tempFileName = $this->createTempFileName('png');

        // Save image
        imagepng($png, $tempFileName);

        return $tempFileName;
    }

    /**
     * Create a temporary file name.
     *
     * If file exists on exit, it'll be automatically removed.
     *
     * @param string $ext File extension.  Defaults to "tmp".
     *
     * @return string
     * @throws \Exception
     */
    protected function createTempFileName($ext = 'tmp')
    {
        $tempFileName         = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'box' . bin2hex(random_bytes(4)) . '.' . $ext;
        $this->tempFileList[] = $tempFileName;
        return $tempFileName;
    }

    /**
     * Remove registered temp files.
     */
    protected function removeTempFiles()
    {
        foreach ($this->tempFileList as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }

        $this->tempFileList = [];
    }

}