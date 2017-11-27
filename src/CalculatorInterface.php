<?php

namespace Estimator\Estimator;

/**
 * Interface CalculatorInterface
 *
 * For all calculators
 */
interface CalculatorInterface
{
    /**
     * @param $message
     * @return void
     */
    public function log($message);

    /**
     * @return array
     */
    public function getLog();

    /**
     * @param $cost
     * @param null $costEffective
     * @return void
     */
    public function writeCost($cost, $costEffective=null);

    /**
     * @return CalculatorResult
     */
    public function getResult();

    /**
     * @return float
     */
    public function calculate();
}