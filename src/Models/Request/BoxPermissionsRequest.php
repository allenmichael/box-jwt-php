<?php

namespace Box\Models\Request;

use Box\Models\Request\BoxRequestModel;
use Box\Exceptions\BoxSdkBadArgumentTypeException;

/**
 * Class BoxPermissionsRequest
 *
 * @property string $can_download
 *
 * @package Box\Models\Request
 */
class BoxPermissionsRequest extends BoxRequestModel
{
    const CAN_DOWNLOAD = "can_download";

    /** @var string */
    protected static $availableParams = [
        self::CAN_DOWNLOAD => null,
    ];

    /**
     * BoxPermissionsRequest constructor.
     *
     * @param string[] $args Array of body params as defined in API.
     *
     * @throws \Box\Exceptions\BoxSdkBadArgumentTypeException
     */
    function __construct(array $args = [])
    {
        if (array_key_exists_and_not_null($args, self::CAN_DOWNLOAD) && gettype($args[self::CAN_DOWNLOAD]) != "boolean") {
            throw new BoxSdkBadArgumentTypeException(self::CAN_DOWNLOAD, "boolean");
        }
        parent::__construct($this, $args);
    }
}