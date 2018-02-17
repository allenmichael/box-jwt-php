<?php

namespace Box\Exceptions;

use Box\Exceptions\BoxSdkException;

class BoxSdkInvalidArgumentException extends BoxSdkException
{
    public function __construct($property, $potentialValues, $code = 0, Exception $previous = null)
    {
        $message = sprintf("An invalid value was provided for the property %s. Please provide one of the following values instead: %s", $property, $potentialValues);
        parent::__construct($message, $code, $previous);
    }
}