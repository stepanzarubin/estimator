<?php

namespace Estimator\Estimator;

/**
 * Class CalculatorResult
 */
class CalculatorResult
{
    /**
     * @var string calculator name
     */
    public $calculator;

    public $cost = 0;
    public $costEffective = 0;

    public $adjustmentAmount = 0;
    public $adjustmentPercent = 0;

    public $log = [];

    /**
     * CalculatorResult constructor.
     * @param string $calculator
     * @param $cost
     * @param $costEffective
     * @param array $log
     */
    public function __construct(string $calculator, $cost=0, $costEffective=0, array $log=[])
    {
        $this->calculator = $calculator;
        $this->cost = $cost;
        $this->costEffective = $costEffective;
        $this->log = $log;

        $this->calculate();
    }

    /**
     * todo can I pass values from Adjustment object? one of values has to be calculated
     */
    public function calculate()
    {
        //is there adjustment?
        if ($this->cost != $this->costEffective)
        {
            $this->adjustmentAmount = $this->costEffective - $this->cost;
            if ($this->cost != 0)
                $this->adjustmentPercent = $this->adjustmentAmount / $this->cost * 100;
        }
    }

    /**
     * @param $cost
     * @param $costEffective
     */
    public function writeCost($cost, $costEffective=null)
    {
        $this->cost = $cost;
        $this->costEffective = $costEffective !== null ? $costEffective : $cost;
        $this->calculate();
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return (array) $this;
    }

    /**
     * @return string
     */
    public function asJson()
    {
        return json_encode($this);
    }
}