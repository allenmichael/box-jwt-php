<?php

namespace Box\Models\Request;

use Box\Models\Request\BoxRequestModel;

/**
 * Class BoxEntityRequest
 *
 * @param string $id
 * @param string $type
 *
 * @package Box\Models\Request
 */
class BoxEntityRequest extends BoxRequestModel
{
    const PARAM_ID          = "id";
    const PARAM_TYPE        = "type";
    const PARAM_DESCRIPTION = "description";
    const PARAM_SHARED_LINK = "shared_link";

    protected static $availableParams = [
        self::PARAM_ID   => null,
        self::PARAM_TYPE => null,
    ];

    const ID   = "id";
    const TYPE = "type";

    function __construct(array $args = [])
    {
        parent::__construct($this, $args);
    }
}