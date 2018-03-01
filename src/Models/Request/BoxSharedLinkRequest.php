<?php

namespace Box\Models\Request;

use Box\Models\Request\BoxPermissionsRequest;
use Box\Models\BoxModelConstants;
use Box\Models\Request\BoxRequestModel;
use Box\Exceptions\BoxSdkInvalidArgumentException;

/**
 * Class BoxSharedLinkRequest
 *
 * @property string $access
 * @property string $password
 * @property string $unshared_at
 * @property string $permissions
 *
 * @package Box\Models\Request
 */
class BoxSharedLinkRequest extends BoxRequestModel
{
    const ACCESS       = "access";
    const PASSWORD     = "password";
    const UNSHARED_AT  = "unshared_at";
    const PERMISSIONS  = "permissions";
    const ACCESS_TYPES = [BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_OPEN, BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_COMPANY, BoxModelConstants::BOX_SHARED_LINK_ACCESS_TYPE_COLLABORATORS];

    /** @var string */
    protected static $availableParams = [
        self::ACCESS      => null,
        self::PASSWORD    => null,
        self::UNSHARED_AT => null,
        self::PERMISSIONS => null,
    ];

    /**
     * BoxSharedLinkRequest constructor.
     *
     * @param string[] $args Array of body params as defined in API.
     *
     * @throws \Box\Exceptions\BoxSdkBadArgumentTypeException
     * @throws \Box\Exceptions\BoxSdkInvalidArgumentException
     */
    function __construct(array $args = [])
    {
        if (array_key_exists_and_not_null($args, self::ACCESS) && !in_array(strtoLower($args[self::ACCESS]), self::ACCESS_TYPES)) {
            throw new BoxSdkInvalidArgumentException(self::ACCESS, implode(', ', self::ACCESS_TYPES));
        }

        if (array_key_exists_and_not_null($args, self::UNSHARED_AT) && !(is_box_date_time($args[self::UNSHARED_AT]))) {

        }

        if (array_key_exists_and_not_null($args, self::PERMISSIONS)) {
            $permissions             = new BoxPermissionsRequest($args[self::PERMISSIONS]);
            $args[self::PERMISSIONS] = $permissions;
        }
        parent::__construct($this, $args);
    }
}