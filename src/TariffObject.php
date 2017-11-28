<?php

namespace Estimator\Estimator;

/**
 * Class TariffObject
 *
 * Contains all the rates required to calculate EvaluationObject cost
 */
abstract class TariffObject
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
     * TariffObject constructor.
     * @param $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        $this->className = get_class($this);
        $this->populate($config);
    }

    public function populate($config) {
        if (!is_object($config))
            throw new \Exception("'Config' has to be an object.");

        foreach ($config as $name => $value)
            if (property_exists($this,$name))
            {
                if (isset($this->map[$name]))
                    $this->instantiateMapObject($name,$value);
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

        //make sure that class name is correct, e.g. gas => GasTariff
        $classNameEnd = ucfirst($name) . 'Tariff';
        $endsCorrectly = substr_compare( $mapClass, $classNameEnd, -strlen( $classNameEnd ) ) === 0;
        if (!$endsCorrectly)
            throw new \Exception("'$mapClass' should end with '$classNameEnd'.");

        if (!class_exists($mapClass))
            throw new \Exception("$mapClass class does not exist.");

        $this->$name = new $mapClass($config);
    }
}