<?php

namespace Box\Models\Request;

use Box\Models\Request\BoxItemRequest;
use Box\Models\Request\BoxEmailRequest;
use Box\Models\Request\BoxEntityRequest;
use Box\Models\BoxModelConstants;
use Box\Exceptions\BoxSdkInvalidArgumentException;
use GuzzleHttp\RequestOptions;

class BoxFileRequest extends BoxItemRequest
{
    /**
     * BoxFileRequest constructor.
     *
     * @param string[] $args Array of body params as defined in API.
     *
     * @throws \Box\Exceptions\BoxSdkException
     */
    function __construct(array $args = [])
    {
        parent::__construct($args);
    }

    /**
     * Converts file request to a Guzzle multipart option.
     *
     * @return array
     */
    public function toOptions()
    {
        return array_merge(
            $this->createFileUploadMultipart()
        );
    }
}