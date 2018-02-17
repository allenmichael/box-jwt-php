<?php

namespace Box\Managers;

use Box\Config\BoxConstants;
use Box\Models\BoxModelConstants;
use Box\Managers\BoxResourceManager;
use Box\Models\Request\BoxFolderRequest;
use Box\Exceptions\BoxSdkException;
use Box\Exceptions\BoxSdkRequiredArgumentException;

/**
 * Class BoxFoldersManager
 *
 * @package Box\Managers
 */
class BoxFoldersManager extends BoxResourceManager
{

    const CREATE_FOLDER_REQUIRED_PROPERTIES = [BoxModelConstants::NAME, BoxModelConstants::PARENT, BoxModelConstants::ID];

    /**
     * BoxFoldersManager constructor.
     *
     * @param \Box\BoxClient $boxClient BoxClient instance.
     */
    function __construct($boxClient)
    {
        parent::__construct($boxClient);
    }

    /**
     * Get folder info.
     *
     * https://developer.box.com/reference#get-folder-info
     *
     * @param string   $id                Folder id.  Root folder is always '0'.
     * @param string[] $fields            Array of fields to return in response.
     * @param string[] $additionalHeaders Additional HTTP header key-value pairs.
     * @param bool     $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFolderInfo($id, $fields, $additionalHeaders = null, $runAsync = false)
    {
        $urlParams = $this->keepNonEmptyParams([BoxConstants::QUERY_PARAM_FIELDS => $fields]);
        $uri       = parent::createUri(BoxConstants::FOLDERS_ENDPOINT_STRING . $id, $urlParams);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::GET, $uri, $additionalHeaders);
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Get folder items.
     *
     * https://developer.box.com/reference#get-a-folders-items
     *
     * @param string   $id                Folder id.  Root folder is always '0'.
     * @param int      $offset            The offset of the item at which to begin the response.
     * @param int      $limit             The maximum number of items to return.If none is provided,
     *                                    the default is 100 and the maximum is 1,000.
     * @param string[] $fields            Array of fields to return in response.
     * @param string[] $additionalHeaders Additional HTTP header key-value pairs.
     * @param bool     $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFolderItems($id, $offset = 0, $limit = null, $fields = null, $additionalHeaders = null, $runAsync = false)
    {
        $urlParams = $this->keepNonEmptyParams([
            BoxConstants::QUERY_PARAM_OFFSET => $offset,
            BoxConstants::QUERY_PARAM_LIMIT  => $limit,
            BoxConstants::QUERY_PARAM_FIELDS => $fields,
        ]);
        $uri       = parent::createUri(BoxConstants::FOLDERS_ENDPOINT_STRING . sprintf(BoxConstants::ITEMS_PATH_STRING, $id), $urlParams);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::GET, $uri, $additionalHeaders);
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Create folder.
     *
     * https://developer.box.com/reference#create-a-new-folder
     *
     * @param \Box\Models\Request\BoxFolderRequest $folderRequest
     * @param string[]                             $fields            Array of fields to return in response.
     * @param string[]                             $additionalHeaders Additional HTTP header key-value pairs.
     * @param bool                                 $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|\Psr\Http\Message\ResponseInterface
     * @throws \Box\Exceptions\BoxSdkException
     * @throws \Box\Exceptions\BoxSdkRequiredArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createFolder(BoxFolderRequest $folderRequest, $fields = null, $additionalHeaders = null, $runAsync = false)
    {
        if (!($folderRequest instanceof BoxFolderRequest)) {
            throw new BoxSdkException('The first argument supplied must be a BoxFolderRequest');
        }

        if (!$folderRequest->paramExists(BoxModelConstants::NAME)) {
            throw new BoxSdkRequiredArgumentException(BoxModelConstants::NAME);
        }
        if (!$folderRequest->paramExists(BoxModelConstants::PARENT)) {
            throw new BoxSdkRequiredArgumentException(BoxModelConstants::PARENT);
        }
        if (!$folderRequest->parent->paramExists(BoxModelConstants::ID)) {
            throw new BoxSdkRequiredArgumentException(BoxModelConstants::ID);
        }

        $urlParams = $this->keepNonEmptyParams([BoxConstants::QUERY_PARAM_FIELDS => $fields]);
        $uri       = parent::createUri(BoxConstants::FOLDERS_ENDPOINT_STRING, $urlParams);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::POST, $uri, $additionalHeaders, $folderRequest->toJson());
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Copy a folder.
     *
     * https://developer.box.com/reference#copy-a-folder
     *
     * @param string   $sourceFolderId      Source folder id.
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
    public function copyFolder($sourceFolderId, $destinationFolderId, $newName = null, $fields = null, $additionalHeaders = null, $runAsync = false)
    {
        $urlParams = $this->keepNonEmptyParams([BoxConstants::QUERY_PARAM_FIELDS => $fields]);
        $uri       = parent::createUri(BoxConstants::FOLDERS_ENDPOINT_STRING . sprintf(BoxConstants::COPY_PATH_STRING, $sourceFolderId), $urlParams);
        $body      = new BoxFolderRequest(['parent' => ['id' => $destinationFolderId]]);
        if (!empty($newName)) {
            $body->name = $newName;
        }
        $request = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::POST, $uri, $additionalHeaders, $body->toJson());
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Delete folder.
     *
     * https://developer.box.com/reference#delete-a-folder
     *
     * @param string   $id                Folder id.
     * @param bool     $recursive         Whether to delete this folder if it has items inside of it.
     * @param string[] $additionalHeaders Additional HTTP header key-value pairs.
     * @param bool     $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteFolder($id, $recursive = null, $additionalHeaders = null, $runAsync = false)
    {
        $recursive = $recursive ? 'true' : null;
        $urlParams = $this->keepNonEmptyParams([BoxConstants::QUERY_PARAM_RECURSIVE => $recursive]);
        $uri       = parent::createUri(BoxConstants::FOLDERS_ENDPOINT_STRING . $id, $urlParams);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::DELETE, $uri, $additionalHeaders);
        return parent::requestTypeResolver($request, [], $runAsync);
    }

    /**
     * Update folder.
     *
     * https://developer.box.com/reference#update-information-about-a-folder
     *
     * @param \Box\Models\Request\BoxFolderRequest $folderRequest
     * @param string[]                             $fields            Array of fields to return in response.
     * @param string[]                             $additionalHeaders Additional HTTP header key-value pairs.
     * @param bool                                 $runAsync          Run asynchronously.
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Box\Exceptions\BoxSdkException
     * @throws \Box\Exceptions\BoxSdkRequiredArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateFolder(BoxFolderRequest $folderRequest, $fields = null, $additionalHeaders = null, $runAsync = false)
    {
        if (!($folderRequest instanceof BoxFolderRequest)) {
            throw new BoxSdkException('The first argument supplied must be a BoxFolderRequest');
        }
        if (!$folderRequest->paramExists(BoxModelConstants::ID)) {
            throw new BoxSdkRequiredArgumentException(BoxModelConstants::ID);
        }

        $urlParams = $this->keepNonEmptyParams([BoxConstants::QUERY_PARAM_FIELDS => $fields]);
        $uri       = parent::createUri(BoxConstants::FOLDERS_ENDPOINT_STRING . $folderRequest->id, $urlParams);
        $request   = parent::alterBaseBoxRequest($this->getBaseBoxRequest(), BoxConstants::PUT, $uri, $additionalHeaders, $folderRequest->toJson());
        return parent::requestTypeResolver($request, [], $runAsync);
    }
}