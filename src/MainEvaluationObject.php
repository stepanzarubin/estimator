<?php

namespace Estimator\Estimator;

/**
 * Class MainEvaluationObject
 *
 * Main evaluation object may consist of many other smaller evaluation objects (services), e.g. "Car" may have "Engine"
 */
abstract class MainEvaluationObject extends EvaluationObject
{
    /**
     * @var EvaluationObject
     */
    public $common;

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

        //this is not necessary because common object should have defaults defined in common class
        //if (!isset($config->common))
            //throw new \Exception("Main evaluation object 'common' has to be defined");

//        echo '<pre>';
//        print_r($config);
//        echo '</pre>';
//        exit;

        /**
         * Making sure main eval object common class exists and common class is initialized with defaults
         */
        //e.g. "CustomCommon" may replace "CarCommon" by rule 'common'=>'CustomCommon'
        if (isset($this->map['common']))
            $this->instantiateMapObject('common');
        else {

            //determine common class name automatically
            //e.g. "CarCommon"
            $commonClass = "{$this->className}Common";
            if (!class_exists($commonClass))
                throw new \Exception("$commonClass class does not exist.");

            $this->common = new $commonClass;
        }
        /* end */

        foreach ($config as $name => $value)
        {
            if (!property_exists($this,$name))
                throw new \Exception("'$name' has to be a part of 'common' object.");

            if (!is_object($value))
                throw new \Exception("'$name' has to be an object.");

            if ($name == 'common')
                $this->common->populate($value);
            elseif (isset($this->map[$name]))
                //map allows to override expected class name, e.g. "CarGas" may replace "Gas" by rule 'gas'=>'CarGas'
                $this->instantiateMapObject($name,$value);
            else {
                //determine class name automatically
                //e.g. "Gas"
                $evaluationObjectClass = ucfirst(strtolower($name));
                if (!class_exists($evaluationObjectClass))
                    throw new \Exception("$evaluationObjectClass class does not exist.");
                $this->$name = new $evaluationObjectClass($value);
            }
        }

//        echo '<pre>';
//        var_dump($this);
//        echo '</pre>';
//        exit;
    }
}