<?php

namespace korchasa\matched;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\DiffOnlyOutputBuilder;

/**
 * Констрейнт для частичного сравнения JSON строк.
 *
 * Места, которые не нужно сравнивать, помечаются в expected символом *
 */
class JsonMatchedConstraint extends Constraint
{
    const ANY_SYMBOL = '*any*';

    /**
     * Ожидаемый статус
     *
     * @var object
     */
    protected $expectedJson;


    /**
     * @param string $expectedJson
     */
    public function __construct($expectedJson = null)
    {
        parent::__construct();
        $this->expectedJson = $expectedJson;
    }//end __construct()


    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $returnResult
     *
     * @return boolean
     */
    public function evaluate(
        $other,
        $description = 'Failed asserting that two jsons are partial equal.',
        $returnResult = false
    ) {
    
        $expected = json_decode($this->expectedJson, true);
        $actual   = json_decode($other, true);

        if (!$this->compare($expected, $actual)) {
            $diffBuilder = new DiffOnlyOutputBuilder("--- Original\n+++ Actual\n");
            $diff = (new Differ($diffBuilder))->diff(
                $this->beautifyJson($this->expectedJson),
                $this->beautifyJson($other)
            );
            throw new ExpectationFailedException(
                trim($description."\n".$diff)
            );
        }

        return true;
    }//end evaluate()


    public function compare($expected, $actual)
    {
        if (is_array($expected)) {
            foreach ($expected as $key => $value) {
                if (!array_key_exists($key, $actual)) {
                    return false;
                }

                if (!$this->compare($expected[$key], $actual[$key])) {
                    return false;
                }
            }

            return true;
        }

        return $expected === $actual || self::ANY_SYMBOL === $expected;
    }//end compare()


    public function toString()
    {
        return sprintf(
            'matches JSON string "%s"',
            $this->expectedJson
        );
    }//end toString()


    protected function beautifyJson($json)
    {
        return json_encode(json_decode($json), (JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }//end beautifyJson()
}//end class
