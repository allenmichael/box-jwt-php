<?php

namespace Box\Managers;

use Box\Config\BoxConstants;
use Box\Models\BoxModelConstants;
use Box\Managers\BoxResourceManager;
use Box\Models\Request\BoxFileRequest;
use Box\Exceptions\BoxSdkException;
use Box\Exceptions\BoxSdkRequiredArgumentException;
use Box\Models\Request\BoxItemRequest;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\StreamInterface;

/**
 * Class BoxFilesManager
 *
 * @package Box\Managers
 */
class BoxFilesManager extends BoxResourceManager
{

    /**
     * BoxFilesManager constructor.
     *
     * @param \Box\BoxClient $boxClient BoxClient instance.
     */
    function __construct($boxClient)
    {
        parent::__construct($boxClient);
    }

    /**
     * Get file info.
     *
     * https://developer.box.com/reference#files
     *
     * @param string   $id                File id.
     * @param string[] $fields            Array of fields to return in response.
     * @param string[] $additionalHeaders Additional HTTP header key-value pairs.
     * @param bool     $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFileInfo($id, $fields, $additionalHeaders = null, $runAsync = false)
    {
        $urlParams = $this->keepNonEmptyParams([BoxConstants::QUERY_PARAM_FIELDS => $fields]);
        $uri       = parent::createUri(BoxConstants::FILES_ENDPOINT_STRING . $id, $urlParams);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::GET, $uri, $additionalHeaders);
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Upload a file.
     *
     * https://developer.box.com/v2.0/reference#upload-a-file
     *
     * @param \Box\Models\Request\BoxFileRequest                $fileRequest       BoxFileRequest instance.
     * @param string|resource|\Psr\Http\Message\StreamInterface $file              File path, file resource or
     *                                                                             StreamInterface instance.
     * @param string[]                                          $additionalHeaders Additional HTTP header key-value
     *                                                                             pairs.
     * @param bool                                              $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Box\Exceptions\BoxSdkException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFile(BoxFileRequest $fileRequest, $file, $additionalHeaders = null, $runAsync = false)
    {
        if (!($fileRequest instanceof BoxFileRequest)) {
            throw new BoxSdkException('The first argument supplied must be a BoxFileRequest');
        }

        // Prepare file hash for verification
        $this->getFileHashHeader($file, $additionalHeaders);

        // Prepare file
        $options = $this->createFileUploadOption($fileRequest, $file);

        $uri     = parent::createUri(BoxConstants::FILES_UPLOAD_ENDPOINT_STRING);
        $request = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::POST, $uri, $additionalHeaders);
        return parent::requestTypeResolver($request, $options, $runAsync);
    }

    /**
     * Delete file.
     *
     * https://developer.box.com/reference#delete-a-file
     *
     * @param string   $id                File id.
     * @param string[] $additionalHeaders Additional HTTP header key-value pairs.
     * @param bool     $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteFile($id, $additionalHeaders = null, $runAsync = false)
    {
        $uri       = parent::createUri(BoxConstants::FILES_ENDPOINT_STRING . $id);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::DELETE, $uri, $additionalHeaders);
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Copy a file.
     *
     * https://developer.box.com/v2.0/reference#copy-a-file
     *
     * @param string   $sourceFileId        Source file id.
     * @param string   $destinationFolderId Destination folder id.  Root folder is always '0'.
     * @param string   $newName             New name of copied folder.
     * @param string[] $fields              Array of fields to return in response.
     * @param string[] $additionalHeaders   Additional HTTP header key-value pairs.
     * @param bool     $runAsync            Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Box\Exceptions\BoxSdkException
     * @throws \Box\Exceptions\BoxSdkInvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function copyFile($sourceFileId, $destinationFolderId, $newName = null, $fields = null, $additionalHeaders = null, $runAsync = false)
    {
        $urlParams = $this->keepNonEmptyParams([BoxConstants::QUERY_PARAM_FIELDS => $fields]);
        $uri       = parent::createUri(BoxConstants::FILES_ENDPOINT_STRING . sprintf(BoxConstants::COPY_PATH_STRING, $sourceFileId), $urlParams);
        $body      = new BoxFileRequest(['parent' => ['id' => $destinationFolderId]]);
        if (!empty($newName)) {
            $body->name = $newName;
        }
        $request = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::POST, $uri, $additionalHeaders, $body->toJson());
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Download file.
     *
     * https://developer.box.com/reference#download-a-file
     *
     * @param string                                            $fileId            File id.
     * @param string|resource|\Psr\Http\Message\StreamInterface $sink              Where the downloaded file will be
     *                                                                             stored
     * @param string                                            $version           Optional file version ID to download
     * @param string[]                                          $additionalHeaders Additional HTTP header key-value
     *                                                                             pairs.
     * @param bool                                              $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function downloadFile($fileId, $sink, $version = null, $additionalHeaders = null, $runAsync = false)
    {
        $urlParams = $this->keepNonEmptyParams([BoxConstants::QUERY_PARAM_VERSION => $version]);
        $options   = $this->createFileDownloadOption($sink);
        $uri       = parent::createUri(BoxConstants::FILES_ENDPOINT_STRING . sprintf(BoxConstants::CONTENT_PATH_STRING, $fileId), $urlParams);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::GET, $uri, $additionalHeaders);
        return parent::requestTypeResolver($request, $options, $runAsync);
    }

    /**
     * Get file hash header.
     *
     * @param string|resource|\Psr\Http\Message\StreamInterface $file   File path, file resource or StreamInterface
     *                                                                  instance.
     * @param array                                             $header Optional header array to append to.
     *
     * @return array
     */
    protected function getFileHashHeader($file, $header = [])
    {
        $header                                   = !is_array($header) ? [] : $header;
        $header[BoxConstants::HEADER_CONTENT_MD5] = file_hash($file);
        return $header;
    }

    /**
     * Create file upload request option for Guzzle.
     *
     * @param \Box\Models\Request\BoxFileRequest                $fileRequest BoxFileRequest instance.
     * @param string|resource|\Psr\Http\Message\StreamInterface $file        File path, file resource or
     *                                                                       StreamInterface instance.
     *
     * @return array
     * @throws \Box\Exceptions\BoxSdkException
     */
    protected function createFileUploadOption(BoxFileRequest $fileRequest, $file)
    {
        if (!$fileRequest->paramExists('name', true)) {
            throw new BoxSdkException('The "name" parameter must exists on BoxFileRequest instance and must not be empty');
        }

        if (!$fileRequest->paramExists('parent', true)) {
            throw new BoxSdkException('The "parent" parameter must exists on BoxFileRequest instance and must not be empty');
        }

        if (
            !(is_string($file) && file_exists($file))
            && !is_resource($file)
            && !($file instanceof StreamInterface)
        ) {
            throw new BoxSdkException('"$file" parameter must be a string path to a valid file, a resource or an instance of ' . StreamInterface::class);
        }

        if (is_string($file) && file_exists($file)) {
            $file = fopen($file, 'r');
        } elseif (is_resource($file)) {
            // nothing to do
        } elseif ($file instanceof StreamInterface) {

        }

        return [
            RequestOptions::MULTIPART => [
                [
                    'name'     => 'attributes',
                    'contents' => $fileRequest->toJson(),
                ],
                [
                    'name'     => BoxConstants::REQUEST_OPTION_MULTIPART_FILE,
                    'contents' => $file
                ],
            ]
        ];
    }

    /**
     * Create file download request option for Guzzle.
     *
     * @param string|resource|\Psr\Http\Message\StreamInterface $sink Where the downloaded file will be stored
     *
     * @return array
     */
    protected function createFileDownloadOption($sink)
    {
        return [RequestOptions::SINK => $sink];
    }
}