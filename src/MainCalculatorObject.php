<?php

namespace Estimator\Estimator;

/**
 * Class MainCalculatorObject
 *
 * Calculates cost and generates output in formats to allow:
 *  1. Visualize results in a human readable format.
 *  2. Easily use it for reports.
 *  3. Send somewhere else.
 *
 * What it should not do:
 *  1. Any operation which is not a calculation.
 *     e.g. it should not save estimate
 */
abstract class MainCalculatorObject extends CalculatorObject
{
    /**
     * Calculators class map
     * @var array
     */
    protected $map = [];

    /**
     * MainCalculatorObject constructor.
     * @param MainEvaluationObject $evaluationObject
     * @param TariffObject|null $tariffObject
     */
    public function __construct(MainEvaluationObject $evaluationObject, TariffObject $tariffObject = null)
    {
        parent::__construct($evaluationObject->common, $evaluationObject, $tariffObject);
    }

    /**
     * @param $serviceName
     * @return mixed
     * @throws \Exception
     */
    protected function getCalculatorClass($serviceName) {
        if (!isset($this->map[$serviceName]))
            throw new \Exception("'$serviceName' calculator class is not defined.");

        $mapClass = $this->map[$serviceName];

        //make sure that class name is correct, e.g. gas => GasCalculator
        $classNameEnd = ucfirst($serviceName) . 'Calculator';
        $endsCorrectly = substr_compare( $mapClass, $classNameEnd, -strlen( $classNameEnd ) ) === 0;
        if (!$endsCorrectly)
            throw new \Exception("'$mapClass' should end with '$classNameEnd'.");

        if (!class_exists($mapClass))
            throw new \Exception("$mapClass class does not exist.");

        return $mapClass;
    }

    /**
     * @return float
     * @throws \Exception
     */
    public function calculate()
    {
        $total = 0;
        $subtotal_index = 1;

        if (!is_array($this->tariffObject->services) || empty($this->tariffObject->services))
            throw new \Exception("{$this->tariffObject->className} has to have filled 'services' array defined.");

        foreach ($this->tariffObject->services as $serviceName)
        {
            //some calculators do not need tariff (e.g. Spend is more car characteristic than tariff)
            //tariff appears when it is applicable for all/multiple instances of eval object
            //if (isset($this->evaluationObject->$serviceName) && isset($this->tariffObject->$serviceName))

            //if eval object has service defined
            if (isset($this->evaluationObject->$serviceName))
            {
                $calculatorClass = $this->getCalculatorClass($serviceName);

                //print_r($calculatorEvaluationObject);exit;
                $calculatorEvaluationObject = $this->evaluationObject->$serviceName;
                $calculatorTariff = isset($this->tariffObject->$serviceName) ? $this->tariffObject->$serviceName : null;
                $calculator = new $calculatorClass($this->common, $calculatorEvaluationObject, $calculatorTariff);

                /**
                 * @var $result CalculatorResult
                 */
                $calculator->calculate();
                $result = $calculator->getResult();

                $this->log($result);

                $subtotalLogMessage = "$total + $result->costEffective = ";
                $total += $result->costEffective;
                $subtotalLogMessage .= "$$total";

                //todo subtotals
                //last iteration should add final total
                //there should be a way to give names to subtotals

                $subtotalCalculatorResult = new CalculatorResult("Subtotal_{$subtotal_index}", $total, $total, [$subtotalLogMessage]);
                $this->log($subtotalCalculatorResult);
                $subtotal_index++;
            }
        }

        //returning only final total amount
        //$lastSubtotal = current(end($log));
        //return $lastSubtotal['costEffective'];

        return $total;
    }
}