<?php declare(strict_types=1);

namespace korchasa\matched;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

class StringConstraint extends Constraint
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @param string $pattern
     */
    public function __construct(string $pattern = null)
    {
        parent::__construct();
        $this->pattern = $pattern;
    }

    /**
     * @param mixed $other
     * @param string $description
     * @param boolean $returnResult
     * @return boolean
     * @throws \Exception
     */
    public function evaluate(
        $other,
        $description = 'Failed asserting that string matched pattern',
        $returnResult = false
    ): bool {
        return Match::string(
            $this->pattern,
            $other,
            Match::ANY_SYMBOL,
            function ($expected, $actual, $message) use ($returnResult) {
                if ($returnResult) {
                    return;
                }
                $diffBuilder = new UnifiedDiffOutputBuilder("--- Pattern\n+++ Actual\n");
                $diff = (new Differ($diffBuilder))->diff($expected, $actual);
                throw new ExpectationFailedException($message."\n".$diff);
            }
        );
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return sprintf(
            'matches string `%s`',
            $this->pattern
        );
    }
}
