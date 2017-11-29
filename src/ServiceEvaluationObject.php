<?php

namespace Estimator\Estimator;

/**
 * Class ServiceEvaluationObject
 *
 * Object, which price is being evaluated (service).
 *
 * Specific features:
 * 1. Ability to set service cost adjustment.
 */
class ServiceEvaluationObject extends EvaluationObject
{
    /**
     * @var EvaluationObjectAdjustment
     */
    public $adjustment;

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
            //changing the order may allow override adjustment class
            if ($name == 'adjustment')
                $this->adjustment = new EvaluationObjectAdjustment($value);
            elseif (isset($this->map[$name]))
                $this->instantiateMapObject($name,$value);
            else
                $this->$name = $value;
    }

    /**
     * @return bool
     */
    public function hasAdjustment()
    {
        return $this->adjustment && $this->adjustment->isActive();
    }
}