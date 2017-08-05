<?php

namespace korchasa\matched\Tests;

use korchasa\matched\JsonMatchedConstraint;
use PHPUnit\Framework\TestCase;

class JsonMatchedConstraintTest extends TestCase
{
    public function testCompareEqual()
    {
        $constraint = new JsonMatchedConstraint(null);
        $this->assertTrue(
            $constraint->compare(
                ['foo' => 'bar'],
                ['foo' => 'bar']
            )
        );
    }


    public function testCompareWithDepth()
    {
        $constraint = new JsonMatchedConstraint(null);
        $this->assertTrue(
            $constraint->compare(
                [
                 'foo' => ['bar' => '*any*'],
                ],
                [
                 'foo' => ['bar' => 11],
                ]
            )
        );
    }

    public function testEvaluateComplex()
    {
        $constraint = new JsonMatchedConstraint(
            '{
            "foo": "bar",
            "baz": "*any*",
            "items": [
                "*any*",
                { 
                    "z": "x",
                    "c": 3
                }    
            ]
        }'
        );

        $this->assertTrue(
            $constraint->evaluate(
                '{
            "foo": "bar",
            "baz": {
                "value": 1
            },
            "items": [
                { 
                    "a": "b",
                    "c": 2
                },
                { 
                    "z": "x",
                    "c": 3
                }    
            ]
        }',
                '',
                true
            )
        );
    }

    /**
     * @expectedException \PHPUnit\Framework\ExpectationFailedException
     */
    public function testEvaluateNegative()
    {
        $constraint = new JsonMatchedConstraint(
            '{
            "foo": "bar",
            "baz": {
                "value": 1
            },
            "items": [
                { 
                    "a": "b",
                    "c": 2
                },
                "*any*"  
            ]
        }'
        );

        $constraint->evaluate(
            '{
                "foo": "bar2",
                "baz": {
                    "value": 12
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
            }'
        );
        $this->fail('Exception not raised');
    }
}
