<?php declare(strict_types=1);

namespace korchasa\matched\Tests;

use korchasa\matched\AssertMatchedTrait;
use PHPUnit\Framework\TestCase;

class AssertMatchedTraitTest extends TestCase
{
    use AssertMatchedTrait;

    public function testAssertJsonMatched()
    {
        static::assertJsonMatched(
            '{
                "foo": "***", 
                "bar": 42
            }',
            '{
                "foo": {
                    "baz": 1
                },
                "bar": 42,
                "baz": "foo"
            }'
        );
    }

    public function testAssertArrayMatched()
    {
        static::assertArrayMatched(
            [
                "foo" => "***", // check only presence
                "bar" => 42 // check presence and value
            ],
            [
                "foo" => [
                    "baz" => 1
                ],
                "bar" => 42,
                "baz" => "foo" // do not check at all
            ]
        );
    }

    public function testAssertStringMatched()
    {
        static::assertStringMatched('cu***mber', 'cucumber');
    }
}
