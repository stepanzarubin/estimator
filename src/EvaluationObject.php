<?php

namespace Estimator\Estimator;

/**
 * Class EvaluationObject
 *
 * Object, which holds payload.
 */
class EvaluationObject
{
    /**
     * @var string
     */
    public $className;

    /**
     * @var array Property => Class
     */
    protected $map = [];

    /**
     * EvaluationObject constructor.
     * @param null $config
     * @throws \Exception
     */
    public function __construct($config = null)
    {
        $this->className = get_class($this);
        $this->populate($config);
    }

    /**
     * @param $config
     * @return bool
     * @throws \Exception
     */
    public function populate($config) {
        if (!$config)
            return false;

        if (!is_object($config))
            throw new \Exception("'Config' has to be an object.");

        foreach ($config as $name => $value)
            if (property_exists($this,$name))
            {
                if (isset($this->map[$name]))
                    $this->instantiateMapObject($name, $value);
                else
                    $this->$name = $value;
            }
            else
                throw new \Exception("Property '$name' does not exist.");
    }

    /**
     * @param $name
     * @param null $config
     * @throws \Exception
     */
    public function instantiateMapObject($name, $config=null)
    {
        $mapClass = $this->map[$name];
        if (!class_exists($mapClass))
            throw new \Exception("$mapClass class does not exist.");

        $this->$name = new $mapClass($config);
    }
}