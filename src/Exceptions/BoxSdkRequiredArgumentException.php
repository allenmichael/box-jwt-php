<?php
namespace Box\Exceptions;

use Box\Exceptions\BoxSdkException;

class BoxSdkRequiredArgumentException extends BoxSdkException {
     public function __construct($property, $code = 0, Exception $previous = null) {
         $message = sprintf("The %s property is required.", $property);
         parent::__construct($message, $code, $previous);
     }
}