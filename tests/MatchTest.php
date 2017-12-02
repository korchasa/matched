<?php declare(strict_types=1);

namespace korchasa\matched\Tests;

use korchasa\matched\Match;
use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    /**
     * @param array $pattern
     * @param mixed $actual
     * @param bool $result
     * @dataProvider arrayProvider
     * @throws \Exception
     */
    public function testArray(array $pattern, $actual, bool $result)
    {
        $this->assertEquals($result, Match::array($pattern, $actual));
    }

    public function arrayProvider()
    {
        return [
            'simple true' => [
                [ 'foo' => 'bar' ],
                [ 'foo' => 'bar' ],
                true
            ],
            'simple false' => [
                [ 'foo' => 'bar' ],
                [ 'foo' => 'baz' ],
                false
            ],
            'partial true' => [
                [ 'foo' => 'bar' ],
                [ 'foo' => 'bar', 'baz' => 42 ],
                true
            ],
            'partial false' => [
                [ 'foo' => 'bar', 'baz' => 42 ],
                [ 'foo' => 'baz' ],
                false
            ],
            'inline string true' => [
                [ 'foo' => 'bar***' ],
                [ 'foo' => 'bar2'],
                true
            ],
            'inline string false' => [
                [ 'foo' => 'bar***'],
                [ 'foo' => 'baz' ],
                false
            ],
            'list true' => [
                [ 'foo' ],
                [ 'foo', 'bar' ],
                true
            ],
            'list false' => [
                [ 'foo', 'bar', 'baz'],
                [ 'foo', 'bar' ],
                false
            ],
            'with depth' => [
                [
                    'foo' => [ 'any' => '***' ],
                    '***'
                ],
                [
                    'foo' => [ 'any' => 11 ],
                    'baz'
                ],
                true
            ],
            'with in string pattern' => [
                [ 'string' => 'a***c' ],
                [ 'string' => "ac" ],
                true
            ]
        ];
    }

    /**
     * @param string $pattern
     * @param mixed $actual
     * @param bool $result
     * @dataProvider jsonProvider
     * @throws \Exception
     */
    public function testJson(string $pattern, $actual, bool $result)
    {
        $this->assertEquals($result, Match::json($pattern, $actual));
    }

    public function jsonProvider()
    {
        return [
            'complex true' => [
                '{
                    "foo": "bar",
                    "baz": "***",
                    "items": [
                        "***",
                        { "z": "x", "c": 3 }    
                    ]
                }',
                '{
                    "foo": "bar",
                    "baz": { "value": 1 },
                    "items": [
                        { "a": "b", "c": 2 },
                        { "z": "x", "c": 3 }    
                    ]
                }',
                true
            ],
            'complex false' => [
                '{
                    "foo": "bar",
                    "baz": { "value": 1 },
                    "items": [
                        { "a": "b",  "c": 2 },
                        "***"  
                    ]
                }',
                '{
                    "foo": "bar2",
                    "baz": { "value": 12 },
                    "items": [
                        { "a2": "b2", "c2": 22 },
                        { "z": "x", "c": 3 }    
                    ]
                }',
                false
            ],
            'missed key items.a' => [
                '{
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
                }',
                '{
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
                }',
                false
            ]
        ];
    }

    public function testJsonWithCustomSymbol()
    {
        $this->assertTrue(
            Match::json(
                '{                  
                    "items": [
                        "%some_value%",
                        { "z": "x", "c": "%some_value%" }    
                    ]
                }',
                '{                    
                    "items": [
                        { "a": "b", "c": 2 },
                        { "z": "x", "c": 3 }    
                    ]
                }',
                '%some_value%'
            )
        );
    }

    /**
     * @param string $pattern
     * @param mixed $actual
     * @param bool $result
     * @dataProvider stringProvider
     * @throws \Exception
     */
    public function testString(string $pattern, $actual, bool $result)
    {
        $this->assertEquals($result, Match::string($pattern, $actual));
    }

    public function stringProvider()
    {
        return [
            'types mismatch' => [ 'foo', 1, false],
            'equals strings' => [ 'foo', 'foo', true ],
            'special symbols protection' => [ '**cumber', 'cucumber', false ],
            'pass begin' => [ '***cumber', 'cucumber', true ],
            'fail begin' => [ '***cumber', 'cucumbez', false ],
            'pass middle' => [ 'cu***mber', 'cucumber', true ],
            'fail middle' => [ 'cu***mber', 'cucumbez', false ],
            'pass end' => [ 'cucu***', 'cucumber', true ],
            'fail end' => [ 'cucu***', 'bucumber', false ],
            'pass multiple' => [ '12345***0ab***f', '1234567890abcdef', true ],
            'fail multiple' => [ '12345***0ab***f', '1234567890abcdee', false ],
            'escaping' => [
                '/V/***class***method.json',
                '/V/B/class_method.json',
                true
            ]
        ];
    }
}
