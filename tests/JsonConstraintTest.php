<?php declare(strict_types=1);

namespace korchasa\matched\Tests;

use korchasa\matched\JsonConstraint;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class JsonConstraintTest extends TestCase
{
    public function testError()
    {
        $constraint = new JsonConstraint('{
            "baz": {
                "value": 1
            },
            "items": [
                { 
                    "a": "b***",
                    "c": 2
                },
                "***"  
            ]
        }');

        try {
            $constraint->evaluate('{
                "baz": {
                    "value": 1
                },
                "items": [
                    { 
                        "a": "b2",
                        "c": 22
                    },
                    { 
                        "z": "x",
                        "c": 3
                    }    
                ]
            }');
            $this->fail('Test must fail with missed key items.a');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<TEXT
Given value of `items.0.c` not match pattern `2`
--- Pattern
+++ Actual
@@ @@
-2
+22

TEXT
                ,
                $e->getMessage()
            );
        }
    }
}
