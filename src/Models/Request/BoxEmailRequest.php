<?php

namespace Box\Models\Request;

use Box\Models\Request\BoxRequestModel;
use Box\Models\BoxModelConstants;
use Box\Exceptions\BoxSdkInvalidArgumentException;

/**
 * Class BoxEmailRequest
 *
 * @property string $access
 *
 * @package Box\Models\Request
 */
class BoxEmailRequest extends BoxRequestModel
{
    const ACCESS       = "access";
    const ACCESS_TYPES = [BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_OPEN, BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_COLLABORATORS];

    /** @var string */
    protected static $availableParams = [
        self::ACCESS => null,
    ];

    /**
     * BoxEmailRequest constructor.
     *
     * @param string[] $args Array of body params as defined in API.
     *
     * @throws \Box\Exceptions\BoxSdkInvalidArgumentException
     */
    function __construct(array $args = [])
    {
        if (array_key_exists_and_not_null($args, self::ACCESS) && !in_array(strtoLower($args[self::ACCESS]), self::ACCESS_TYPES)) {
            throw new BoxSdkInvalidArgumentException(self::ACCESS, implode(', ', self::ACCESS_TYPES));
        }
        parent::__construct($this, $args);
    }
}