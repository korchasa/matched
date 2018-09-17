<?php declare(strict_types=1);

namespace korchasa\matched\Tests;

use korchasa\matched\ArrayConstraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class ArrayConstraintTest extends TestCase
{
    public function testError()
    {
        $constraint = new ArrayConstraint([ 'foo' => 'bar***', 'baz' => 42 ]);

        try {
            $constraint->evaluate(['foo' => 'bar2']);
            $this->fail('Test must fail on second symbol');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<TEXT
Given value has no key `baz`
--- Pattern
+++ Actual
@@ @@
 array (
-  'foo' => 'bar***',
-  'baz' => 42,
+  'foo' => 'bar2',
 )

TEXT
                ,
                $e->getMessage()
            );
        }
    }

    public function testToString()
    {
        $this->assertEquals(
            'matches array `{"foo":"bar***","baz":42}`',
            (new ArrayConstraint([ 'foo' => 'bar***', 'baz' => 42 ]))->toString()
        );
    }
}
