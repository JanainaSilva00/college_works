<?php

/**
 * Class Simpsons
 * This class calculates the integral value using the Simpson Rule
 * It is not a complete version and must not work if the x value repeat on function values array
 */
class Simpson
{
    private $_function;
    private $_h;
    private $_initialInterval;
    private $_finalInterval;
    private $_functionValues;
    private $_intervalQty;

    public function __construct($expression, $initialInterval, $finalInterval, $intervalQty)
    {
        $this->setFunctionExpression($expression);
        $this->setInitialInterval($initialInterval);
        $this->setFinalInterval($finalInterval);
        $this->setIntervalQty($intervalQty);
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
            $x += $this->getH();
            $this->_functionValues["{$x}"] = $this->calculateExpression($x);
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
        $expression = str_replace('x', $value, strtolower($this->getFunctionExpression()));
        return eval("return {$expression};");
    }

    /**
     * Sum all the results for f(x)
     * @return int|mixed
     */
    protected function functionValueSum($type = 'odd')
    {
        $sum = 0;

        $functionValue = $this->_functionValues;
        unset($functionValue[$this->getInitialInterval()]);
        unset($functionValue[$this->getFinalInterval()]);

        $count = 1;
        foreach ($functionValue as $value) {
            if ($type == 'odd' && $count % 2 != 0) {
                $sum += $value;
            } else if ($type == 'even' && $count % 2 == 0) {
                $sum += $value;
            }
            $count++;
        }

        return $sum;
    }

    public function integral()
    {
        var_dump("X => " . implode(" | ", array_keys($this->_functionValues)));
        var_dump("f(x) => " . implode(" | ", array_values($this->_functionValues)));

        $integral = ($this->getH() / 3) * (
            $this->_functionValues[$this->getInitialInterval()]
            + (4 *  $this->functionValueSum())
            + (2 *  $this->functionValueSum('even'))
            + $this->_functionValues[$this->getFinalInterval()]
            );

        var_dump("Resultado: " . $integral);
    }
}

$e = "(sqrt(x)) + (1 / x)";
$trapezeRule = new Simpson($e, "1.8", "1.4", 4);
$trapezeRule->integral();
