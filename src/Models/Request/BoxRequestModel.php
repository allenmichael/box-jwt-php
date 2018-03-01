<?php

namespace Box\Models\Request;

use Box\Models\BoxModel;

abstract class BoxRequestModel extends BoxModel
{
    /**
     * BoxRequestModel constructor.
     *
     * @param BoxModel|array $source         Source BoxModel or array of params for new instance.
     * @param array          $args           Array of params for request in key-value format.
     */
    function __construct($source, $args)
    {
        parent::__construct();
        $args = parent::mergeParams($source, $args);
        parent::resolveModelParams($this, $args);
    }

    function toJson()
    {
        return json_encode($this->params);
    }
}