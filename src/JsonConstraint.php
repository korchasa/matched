<?php

namespace korchasa\matched;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\DiffOnlyOutputBuilder;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

class JsonConstraint extends Constraint
{
    /**
     * @var object
     */
    protected $pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern = null)
    {
        parent::__construct();
        $this->pattern = $pattern;
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $returnResult
     *
     * @return boolean
     */
    public function evaluate(
        $other,
        $description = 'Failed asserting that two jsons are matched pattern',
        $returnResult = false
    ) {
        return Match::json($this->pattern, $other, Match::ANY_SYMBOL, function ($expected, $actual, $message) {
            $diffBuilder = new UnifiedDiffOutputBuilder("--- Original\n+++ Actual\n");
            $diff = (new Differ($diffBuilder))->diff(var_export($expected, true), var_export($actual, true));
            throw new ExpectationFailedException($message."\n".$diff);
        });
    }

    /**
     * @return string
     */
    public function toString()
    {
        return sprintf(
            'matches JSON string "%s"',
            $this->pattern
        );
    }
}
