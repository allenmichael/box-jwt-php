<?php

namespace Box\Models\Request;

use Box\Models\Request\BoxItemRequest;
use Box\Models\Request\BoxEmailRequest;
use Box\Models\Request\BoxEntityRequest;
use Box\Models\BoxModelConstants;
use Box\Exceptions\BoxSdkInvalidArgumentException;

/**
 * Class BoxFolderRequest
 *
 * @property string $folder_upload_email
 * @property string $owned_by
 * @property string $sync_state
 *
 * @package Box\Models\Request
 */
class BoxFolderRequest extends BoxItemRequest
{
    const FOLDER_UPLOAD_EMAIL = "folder_upload_email";
    const OWNED_BY            = "owned_by";
    const SYNC_STATE          = "sync_state";
    const SYNC_STATE_VALUES   = [BoxModelConstants::BOX_SYNC_STATE_TYPE_SYNCED, BoxModelConstants::BOX_SYNC_STATE_TYPE_NOT_SYNCED, BoxModelConstants::BOX_SYNC_STATE_TYPE_PARTIALLY_SYNCED];

    /** @var string */
    protected static $availableParams = [
        self::FOLDER_UPLOAD_EMAIL => null,
        self::OWNED_BY => null,
        self::SYNC_STATE => null,
    ];

    /**
     * BoxFolderRequest constructor.
     *
     * @param string[] $args Array of body params as defined in API.
     *
     * @throws \Box\Exceptions\BoxSdkException
     * @throws \Box\Exceptions\BoxSdkInvalidArgumentException
     */
    function __construct(array $args = [])
    {
        if (array_key_exists_and_not_null($args, self::FOLDER_UPLOAD_EMAIL)) {
            $args[self::FOLDER_UPLOAD_EMAIL] = new BoxEmailRequest($args[self::FOLDER_UPLOAD_EMAIL]);
        }

        if (array_key_exists_and_not_null($args, self::OWNED_BY)) {
            $args[self::OWNED_BY] = new BoxEntityRequest($args[self::OWNED_BY]);
        }

        if (array_key_exists_and_not_null($args, self::SYNC_STATE) && !in_array(strtoLower($args[self::SYNC_STATE]), self::SYNC_STATE_VALUES)) {
            throw new BoxSdkInvalidArgumentException(self::ACCESS, implode(', ', self::SYNC_STATE_VALUES));
        }

        parent::__construct($args);
    }
}