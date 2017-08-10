<?php

namespace korchasa\matched\Tests;

use korchasa\matched\ArrayConstraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class ArrayConstraintTest extends TestCase
{
    public function testError()
    {
        $constraint = new ArrayConstraint([ 'foo' => 'bar', 'baz' => 42 ]);

        try {
            $constraint->evaluate(['foo' => 'bar']);
            $this->fail('Test must fail on second symbol');
        } catch (ExpectationFailedException $e) {
            $this->assertNotFalse(
                strpos($e->getMessage(), "'foo' => 'bar',\n-  'baz' => 42,")
            );
        }
    }
}
