<?php

/**
 * Class TrapezeRule
 * This class calculates the integral value using the Trapeze Rule
 * It is not a complete version and must not work if the x value repeat on function values array
 */
class TrapezeRule
{
    private $_function;
    private $_h;
    private $_initialInterval;
    private $_finalInterval;
    private $_functionValues;
    private $_intervalQty;
    private $_hasRaised;

    public function __construct($expression, $initialInterval, $finalInterval, $intervalQty, $hasRaised)
    {
        $this->setFunctionExpression($expression);
        $this->setInitialInterval($initialInterval);
        $this->setFinalInterval($finalInterval);
        $this->setIntervalQty($intervalQty);
        $this->setHasRaised($hasRaised);
        $this->setH($this->calcHValue());
        $this->calcFunctionValues();
    }

    /**
     * @param string|array $function
     */
    public function setFunctionExpression($function)
    {
        $this->_function = $function;
    }

    /**
     * @return string|array
     */
    public function getFunctionExpression()
    {
        return $this->_function;
    }

    /**
     * @param bool $raised
     */
    public function setHasRaised($raised)
    {
        $this->_hasRaised = $raised;
    }

    /**
     * @return bool
     */
    public function getHasRaised()
    {
        return $this->_hasRaised;
    }

    /**
     * @param float $value
     */
    public function setH($value)
    {
        $this->_h = $value;
    }

    /**
     * @return float
     */
    public function getH()
    {
        return $this->_h;
    }

    /**
     * @param float $value
     */
    public function setInitialInterval($value)
    {
        $this->_initialInterval = $value;
    }

    /**
     * @return float
     */
    public function getInitialInterval()
    {
        return $this->_initialInterval;
    }

    /**
     * @param float $value
     */
    public function setFinalInterval($value)
    {
        $this->_finalInterval = $value;
    }

    /**
     * @return float
     */
    public function getFinalInterval()
    {
        return $this->_finalInterval;
    }

    /**
     * @param int $value
     */
    public function setIntervalQty($value)
    {
        $this->_intervalQty = $value;
    }

    /**
     * @return int
     */
    public function getIntervalQty()
    {
        return $this->_intervalQty;
    }

    /**
     * Calculates the h value
     *
     * @return float
     */
    protected function calcHValue()
    {
        return ($this->getInitialInterval() - $this->getFinalInterval()) / $this->getIntervalQty();
    }

    /**
     * Generate table with f(x) values
     */
    protected function calcFunctionValues()
    {
        $aValue = $this->getFinalInterval();
        $bValue = $this->getInitialInterval();

        $this->_functionValues["{$aValue}"] = $this->calculateExpression($aValue);

        $x = $aValue;
        for ($i = 1; $i <= $this->getIntervalQty(); $i++) {
            $this->_functionValues["{$x}"] = $this->calculateExpression($x);
            $x += $this->getH();
        }

        $this->_functionValues["{$bValue}"] = $this->calculateExpression($bValue);
    }

    /**
     * Calculates a string expression
     * @param $value
     * @param null $expression
     * @return mixed
     */
    protected function calculateExpression($value)
    {
        $expression = $this->getFunctionExpression();

        if ($this->getHasRaised()) {
            $raisedExpression = str_replace('x', $value, $expression['raised']);
            $raised =  eval("return {$raisedExpression};");
            return pow($expression['number'], $raised);
        }

        $expression = str_replace('x', $value, strtolower($expression));
        return eval("return {$expression};");
    }

    /**
     * Sum all the results for f(x)
     * @return int|mixed
     */
    protected function functionValueSum()
    {
        $sum = 0;

        $functionValue = $this->_functionValues;
        unset($functionValue[$this->getInitialInterval()]);
        unset($functionValue[$this->getFinalInterval()]);

        foreach ($functionValue as $value) {
            $sum += $value;
        }

        return $sum;
    }

    public function integral()
    {
        var_dump("X => " . implode(" | ", array_keys($this->_functionValues)));
        var_dump("f(x) => " . implode(" | ", array_values($this->_functionValues)));
        $integral = ($this->getH() / 2) * (
            $this->_functionValues[$this->getInitialInterval()]
            + (2 * $this->functionValueSum())
            + $this->_functionValues[$this->getFinalInterval()]
            );
        var_dump("Resultado: " . $integral);
    }
}

$e = ['number' => exp(1), "raised" => "-(x * x)"];

$trapezeRule = new TrapezeRule($e, 3, 1, 4, true);
$trapezeRule->integral();
