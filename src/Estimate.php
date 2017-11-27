<?php

namespace Estimator\Estimator;

/**
 * Holds all estimate data
 * Class Estimate
 */
class Estimate
{
    /**
     * @var array
     */
    public $evaluationObject;

    /**
     * @var array
     */
    public $tariffObject;

    /**
     * @var array
     */
    public $result;

    public function __construct($evaluationObject, $tariffObject, $calculatorResult)
    {
        $this->evaluationObject = $evaluationObject;
        $this->tariffObject = $tariffObject;
        $this->result = $calculatorResult;
    }

    /**
     * @return string
     */
    public function asJson() {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
}