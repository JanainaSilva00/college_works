<?php

class Lagrange {
    private $x0;
    private $x1;
    private $x2;

    private $expressionsResultArray;

    public function setX0($x0)
    {
        $this->x0 = $x0;
    }

    public function setX1($x1)
    {
        $this->x1 = $x1;
    }

    public function setX2($x2)
    {
        $this->x2 = $x2;
    }

    /**
     * @param $number
     * @param string $firstNumber
     * @return array
     */
    private function dividendExpressionFormat($number, $firstNumber = "x")
    {
        return ['first_number' => $firstNumber, 'signal' => '-', 'last_number' => $number];
    }

    /**
     * @param $firstNumber
     * @param $secondNumber
     * @return array
     */
    private function dividerExpressionFormat($firstNumber, $secondNumber)
    {
        return ['first_number' => $firstNumber, 'signal' => '-', 'last_number' => $secondNumber];
    }

    /**
     * Calculate the L expression
     * This function must be broken in some other parts by I will not do this right now
     * @param $lNumber
     */
    public function calcL($lNumber)
    {
        if ($lNumber == 0) {
            $dividendFirstPart = $this->dividendExpressionFormat($this->x1);
            $dividendSecondPart = $this->dividendExpressionFormat($this->x2);

            $dividerFirstPart = $this->dividerExpressionFormat($this->x0, $this->x1);
            $dividerSecondPart = $this->dividerExpressionFormat($this->x0, $this->x2);
        } else if ($lNumber == 1) {
            $dividendFirstPart = $this->dividendExpressionFormat($this->x0);
            $dividendSecondPart = $this->dividendExpressionFormat($this->x2);

            $dividerFirstPart = $this->dividerExpressionFormat($this->x1, $this->x0);
            $dividerSecondPart = $this->dividerExpressionFormat($this->x1, $this->x2);
        } else {
            $dividendFirstPart = $this->dividendExpressionFormat($this->x0);
            $dividendSecondPart = $this->dividendExpressionFormat($this->x1);

            $dividerFirstPart = $this->dividerExpressionFormat($this->x2, $this->x0);
            $dividerSecondPart = $this->dividerExpressionFormat($this->x2, $this->x1);
        }

        $dividend = [$dividendFirstPart, $dividendSecondPart];
        $divider = [$dividerFirstPart, $dividerSecondPart];
        $this->formLExpression($lNumber, $dividend, $divider);

        return $dividend;
    }

    /**
     * @param $fNumber
     * @param $xValue
     * @return array
     */
    public function calcF($fNumber, $xValue)
    {
        $expression = $this->expressionsResultArray[$fNumber];
        $fvalue = 1 / $xValue;
        return ['f' => $fvalue, 'expression' => $expression];
    }

    public function pExpressionTable()
    {
        $table = "<table>   
                    <tbody>
                        <tr>
                            <td>P(X) = &nbsp;&nbsp;</td>";

        for ($i = 0; $i < 3; $i++) {
            $expression = $this->expressionsResultArray[$i];
            $fvalue = 1 / $this->{'x' . $i};
            $table .= "
                <td>$fvalue</td>
                <td>$expression[0]&nbsp;&nbsp;</td>";
        }

        $table .= "
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>--------------</td>
                <td></td>
                <td>--------------</td>
                <td></td>
                <td>--------------</td>
            </tr>
            <tr>
                <td></td>
        ";

        for ($i = 0; $i < 3; $i++) {
            $expression = $this->expressionsResultArray[$i];
            $table .= "
                <td></td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$expression[1]</td>";
        }

        $table .= "</tr>
            </tbody>
        </table>
        <hr>";

        echo $table;
    }

    /**
     * Calculates a string expression
     * @param $expression
     * @return float|int
     */
    private function expressionResult($expression)
    {
        $firstExpression = is_array($expression[0]) ? implode('', $expression[0]) : $expression[0];
        $secondExpression = is_array($expression[1]) ? implode('', $expression[1]) : $expression[1];
        return eval("return {$firstExpression};") * eval("return {$secondExpression};");
    }

    private function formLExpression($lNumber, $dividends, $dividers)
    {
        $line1 = "L{$lNumber} (x) = <br>";
        $line2 = '';
        foreach ($dividends as $dividend) {
            $line2 .= "(";
                foreach ($dividend as $part) {
                    $line2 .= " " . $part;
                }
            $line2 .= ")";
        }
        $line3 = '';
        foreach ($dividers as $divider) {
            $line3 .= "(";

            foreach ($divider as $part) {
                $line3 .= " " . $part ;
            }

            $line3 .= ")";
        }

        $line4 = $this->expressionResult($dividers);

        $this->expressionsResultArray[] = [$line2, $line4];
        $this->lExpressionTable($line1, $line2, $line3, $line4);
    }

    /**
     * Display a table with L expressions
     * @param $line1
     * @param $line2
     * @param $line3
     * @param $line4
     */
    private function lExpressionTable($line1, $line2, $line3, $line4)
    {
        echo "<table>   
               <tbody>
                   <tr>
                        <td>{$line1}</td>
                        <td>{$line2}</td>
                        <td></td>
                        <td>$line2</td>
                   </tr>
                   <tr>
                        <td></td>
                        <td>------------------- </td>
                        <td>=</td>
                        <td>-------------------</td>
                   </tr>
                   <tr>
                        <td></td>
                        <td>$line3</td>
                        <td></td>
                        <td>$line4</td>
                  </tr>     
               </tbody>
        </table>
        <hr>
        ";
    }
}

$lagrange = new Lagrange();
$lagrange->setX0(2);
$lagrange->setX1(2.5);
$lagrange->setX2(4);

$lagrange->calcL(0);
$lagrange->calcF(0, 2);
$lagrange->calcL(1);
$lagrange->calcL(2);

$lagrange->pExpressionTable();