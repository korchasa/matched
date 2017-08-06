<?php

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
                    "a": "b",
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
                        "a2": "b2",
                        "c2": 22
                    },
                    { 
                        "z": "x",
                        "c": 3
                    }    
                ]
            }');
            $this->fail('Test must fail with missed key items.a');
        } catch (ExpectationFailedException $e) {
            $this->assertNotFalse(
                strpos($e->getMessage(), "-  'a' => 'b',\n-  'c' => 2,\n+  'a2' => 'b2',\n+  'c2' => 22,")
            );
        }
    }
}
