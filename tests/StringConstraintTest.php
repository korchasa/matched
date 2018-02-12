<?php declare(strict_types=1);

namespace korchasa\matched\Tests;

use korchasa\matched\StringConstraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class StringConstraintTest extends TestCase
{
    public function testError()
    {
        $constraint = new StringConstraint('some***short***string');

        try {
            $constraint->evaluate('sOme short string');
            $this->fail('Test must fail on second symbol');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<TEXT
Given value not match pattern
--- Pattern
+++ Actual
@@ @@
-some***short***string
+sOme short string

TEXT
                ,
                $e->getMessage()
            );
        }
    }

    public function testToString()
    {
        $this->assertEquals(
            'matches string `some***short***string`',
            (new StringConstraint('some***short***string'))->toString()
        );
    }
}
