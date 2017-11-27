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
     * TariffObject constructor.
     * @param $config
     * @throws Exception
     */
    public function __construct($config)
    {
        $this->className = get_class($this);

        if (!is_object($config))
            throw new Exception("'Config' has to be an object.");

        foreach ($config as $name => $value)
            if (property_exists($this,$name))
            {
                if (is_object($value))
                {
                    $tariffClass = ucfirst($name) . 'Tariff';
                    if (!class_exists($tariffClass))
                        throw new Exception("$tariffClass class does not exist.");
                    $this->$name = new $tariffClass($value);
                }
                else
                    $this->$name = $value;
            }
    }
}