<?php

namespace Estimator\Estimator;

//require_once 'CalculatorInterface.php';
//require_once 'CalculatorResult.php';

/**
 * Class CalculatorObject
 *
 * Calculates cost and generates output in formats to allow:
 *  1. Visualize results in a human readable format.
 *  2. Easily use it for reports.
 *  3. Send somewhere else.
 */
abstract class CalculatorObject implements CalculatorInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Main evaluation object common data
     * @var EvaluationObject
     */
    public $common;

    /**
     * @var EvaluationObject
     */
    public $evaluationObject;

    /**
     * @var TariffObject
     */
    public $tariffObject;

    /**
     * @var
     */
    public $configObject;

    /**
     * @var CalculatorResult
     */
    protected $result;

    /**
     * CalculatorObject constructor.
     * @param EvaluationObject $common
     * @param EvaluationObject $evaluationObject
     * @param TariffObject|null $tariffObject
     */
    public function __construct(EvaluationObject $common, EvaluationObject $evaluationObject, TariffObject $tariffObject = null)
    {
        //"ServiceCalculator" => "Service" ("GasCalculator" => "Gas")
        $this->name = str_ireplace('calculator', '', get_class($this));
        $this->common = $common;

        $this->evaluationObject = $evaluationObject;
        $this->tariffObject = $tariffObject;
        $this->result = new CalculatorResult($this->name);
    }

    /**
     * @param $message
     */
    public function log($message)
    {
        $this->result->log[] = $message;
    }

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->result->log;
    }

    /**
     * @param $cost
     * @param $costEffective
     */
    public function writeCost($cost, $costEffective=null)
    {
        if ($costEffective === null)
            $costEffective = $cost;
        $this->result->writeCost($cost, $costEffective);
    }

    /**
     * @return CalculatorResult
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return float
     */
    public function getEffectiveCost()
    {
        return $this->result->costEffective;
    }

    /**
     * @return float
     */
    public function calculate()
    {
        return 0.00;
    }

    /**
     * @param $total
     * @return float effective
     */
    public function applyAdjustment($total)
    {
        //apply adjustment
        if ($this->evaluationObject->hasAdjustment())
        {
            $adjustmentDescription = $this->evaluationObject->adjustment->getDescription();
            $this->log("Applying adjustment: $adjustmentDescription.");
            $effective = $this->evaluationObject->adjustment->calculate($total);
            $this->log("\"$this->name\" cost = $$effective");
            return $effective;
        }
        else
            return $total;
    }
}