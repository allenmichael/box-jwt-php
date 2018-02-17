<?php

namespace Box\Models\Request;

use Box\Models\Request\BoxRequestModel;
use Box\Models\Request\BoxEntityRequest;
use Box\Models\BoxModelConstants;
use Box\Exceptions\BoxSdkException;

/**
 * Class BoxItemRequest
 *
 * @property string[]|\Box\Models\BoxModel $parent
 * @property string                        $name
 * @property string                        $description
 * @property string                        $shared_link
 *
 * @package Box\Models\Request
 */
class BoxItemRequest extends BoxEntityRequest
{
    const PARAM_PARENT      = "parent";
    const PARAM_NAME        = "name";
    const PARAM_DESCRIPTION = "description";
    const PARAM_SHARED_LINK = "shared_link";

    protected static $availableParams = [
        self::PARAM_PARENT      => null,
        self::PARAM_NAME        => null,
        self::PARAM_DESCRIPTION => null,
        self::PARAM_SHARED_LINK => null,
    ];

    function __construct(array $args = [])
    {
        if (array_key_exists_and_not_null($args, self::PARAM_PARENT)) {
            $parent                   = new BoxEntityRequest($args[self::PARAM_PARENT]);
            $args[self::PARAM_PARENT] = $parent;
        }

        if (array_key_exists_and_not_null($args, self::PARAM_NAME) && strlen($args[self::PARAM_NAME]) > BoxModelConstants::BOX_FOLDER_AND_FILE_NAME_CHARACTER_LIMIT) {
            throw new BoxSdkException(sprintf("%s property must be shorter than %s charaters", self::PARAM_NAME, BoxModelConstants::BOX_FOLDER_AND_FILE_NAME_CHARACTER_LIMIT));
        }

        parent::__construct($args);
    }

}