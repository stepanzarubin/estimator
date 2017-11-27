<?php

namespace Estimator\Estimator;

/**
 * Class EvaluationObjectAdjustment
 *
 * 1. Applied to ServiceEvaluationObject
 * 2. Can be both negative/positive amount/percent
 */
class EvaluationObjectAdjustment
{
    /**
     * When amount is set, percent will be ignored
     * @var float
     */
    public $amount = 0.00;
    public $percent = 0.00;

    /**
     * EvaluationObjectDiscount constructor.
     * @param null $config
     */
    public function __construct($config = null)
    {
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
            $this->$name = $value;

        if ($this->amount != 0 && $this->percent != 0)
            throw new \Exception("Adjustment should be either amount or percent, but not both.");
    }

    /**
     * Cannot adjust cost to negative
     *
     * @param $cost
     * @return float
     */
    public function calculate($cost)
    {
        if ($this->amount != 0)
            $effective = $cost + $this->amount;
        elseif ($this->percent != 0)
        {
            $effective = $cost * (1 + $this->percent/100);
            $effective = round($effective, 2);
        }

        return $effective > 0 ? $effective : 0.00;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->amount != 0 || $this->percent != 0;
    }

    /**
     * todo defined currency sign
     * Used currency sign https://en.wikipedia.org/wiki/Currency_sign_(typography)
     * ¤
     *
     * @return string
     */
    public function getDescription()
    {
        $r = '';
        if ($this->amount != 0)
            $r = $this->amount > 0 ? "+$$this->amount" : sprintf("-%s$",abs($this->amount));
        elseif ($this->percent != 0)
            $r = $this->percent > 0 ? "+$this->percent%" : "$this->percent%";

        return $r;
    }
}