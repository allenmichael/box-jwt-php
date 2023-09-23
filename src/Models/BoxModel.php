<?php

namespace Box\Models;

abstract class BoxModel implements \JsonSerializable
{
    /** @var array $availableParams */
    protected static $availableParams = [];

    /** @var array $params */
    protected $params = [];

    /**
     * BoxModel constructor.
     */
    public function __construct()
    {
        $this->registerAllParams();
    }

    /**
     * Returns data that can be serialized when calling json_encode().
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->params;
    }

    /**
     * Param getter
     *
     * @param string $name Name of parameter.
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }
        return null;
    }

    /**
     * Param setter.
     *
     * @param string $name  Name of parameter.
     * @param mixed  $value Parameter value.
     */
    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function paramIsSet($name)
    {
        return isset($this->params[$name]);
    }

    /**
     * Remove a param.
     *
     * @param string $name Name of parameter.
     */
    public function unsetParam($name)
    {
        if (array_key_exists($name, $this->params)) {
            unset($this->params[$name]);
        }
    }

    /**
     * Register params.
     *
     * @param array $params Parameter array.
     */
    protected function registerParams($params)
    {
        $this->params = array_merge($this->params, $params);
    }

    /**
     * Register all available params into params property.
     */
    protected function registerAllParams()
    {
        $allClasses = class_parents($this);
        if (!empty($allClasses)) {
            $allClasses   = array_values(array_reverse($allClasses));
            $allClasses[] = get_called_class();;
            foreach ($allClasses as $class) {
                if (property_exists($class, 'availableParams')) {
                    $this->registerParams($class::$availableParams);
                }
            }
        }
    }

    /**
     * Returns true if param exists.
     *
     * @param string|array $names                 Name or array of names of parameters
     * @param bool         $checksIfNotNull       If true, parameter will be checked if it is not null.
     * @param bool         $checkIfNotEmptyString If true and if parameter is a string, it will be checked if it is not
     *                                            an empty string.
     *
     * @return bool
     */
    public function paramExists($names, $checksIfNotNull = true, $checkIfNotEmptyString = true)
    {
        if (!is_array($names)) {
            $names = [$names];
        }

        $paramExists = false;

        foreach ($names as $name) {
            $paramExists = array_key_exists($name, $this->params);

            // if param doesn't exist, nothing left to check
            if (!$paramExists) {
                break;
            }

            if ($checksIfNotNull) {
                $paramExists = $paramExists && !is_null($this->params[$name]);
            }

            if ($checkIfNotEmptyString && is_string($this->params[$name])) {
                $paramExists = $paramExists && strlen($this->params[$name]);
            }

            // should we continue to the next param?
            if (!$paramExists) {
                break;
            }
        }

        return $paramExists;
    }

    /**
     * @param BoxModel|array $source
     * @param array          $overwrites
     *
     * @return array
     */
    protected static function mergeParams($source, $overwrites)
    {
        if ($source instanceof self) {
            $source = $source->params;
        }

        return array_merge($source, $overwrites);
    }

    /**
     * Choose model param value from provide argument array.  If param doesn't exist on argument array, it'll default
     * to the one on the BoxModel.
     *
     * @param \Box\Models\BoxModel $obj  BoxModel instance.
     * @param array                $args Argument array.
     * @param string               $name Name of parameter.
     *
     * @return mixed|null
     */
    protected static function chooseModelPropertyValue(BoxModel $obj, $args, $name)
    {
        return (isset($args[$name])) ? $args[$name] : $obj->$name;
    }

    /**
     * Copies over parameters defined on BoxModel index defined on argument array.  Discards parameters that are null
     * in BoxModel instance.
     *
     * @param \Box\Models\BoxModel $obj
     * @param array                $args Argument array.
     */
    protected static function resolveModelParams(BoxModel $obj, $args)
    {
        // get ONLY keys in $args that exist in our params array
        $onlyExistingParams = array_intersect_key($args, $obj->params);

        // replace our local copy
        $obj->params = array_replace($obj->params, $onlyExistingParams);

        /**
         * Some properties such as `sync_state` (used when performing folder update) unlike other properties
         * cannot be null.  If the `sync_state` is not passed in as a parameter, the
         * default null value once passed over to Box will result in a error:
         *
         *     Client error: `PUT https://api.box.com/2.0/folders/<id> resulted in a `400 Bad Request`
         *         missing_parameter: `sync_state` is required
         *
         * In other words, sync_state, if it is provided, cannot be null.
         *
         * Hence, we'll, we unset null properties here.
         */
        $obj->params = array_filter($obj->params, function ($arrayItem) {
            return !is_null($arrayItem);
        });
    }
}