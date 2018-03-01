<?php

namespace Box\Exceptions;

use Box\Exceptions\BoxSdkException;

class BoxSdkBadArgumentTypeException extends BoxSdkException
{
    public function __construct($property, $correctType, $code = 0, Exception $previous = null)
    {
        $message = sprintf("The value for %s must be a %s value.", $property, $correctType);
        parent::__construct($message, $code, $previous);
    }
}