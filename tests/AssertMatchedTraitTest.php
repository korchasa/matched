<?php declare(strict_types=1);

namespace korchasa\matched\Tests;

use korchasa\matched\AssertMatchedTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class AssertMatchedTraitTest extends TestCase
{
    use AssertMatchedTrait;

    public function testAssertJsonMatched(): void
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

    public function testAssertJsonMatchedWithError(): void
    {
        try {
            static::assertJsonMatched('{"foo": 1}', '{"bar": 2}');
            $this->fail();
        } catch (ExpectationFailedException $e) {
        }
    }

    public function testAssertArrayMatched(): void
    {
        static::assertArrayMatched(
            [
                'foo' => '***', // check only presence
                'bar' => 42 // check presence and value
            ],
            [
                'foo' => [
                    'baz' => 1,
                ],
                'bar' => 42,
                'baz' => 'foo' // do not check at all
            ]
        );
    }

    public function testAssertArrayMatchedWithIterableObject(): void
    {
        $pattern = new \SplStack();
        $pattern->push('***');
        $pattern->push(42);

        $actual = new \SplStack();
        $actual->push(['baz' => 1]);
        $actual->push(42);
        $actual->push('foo');

        static::assertArrayMatched($pattern, $actual);
    }

    public function testAssertArrayMatchedWithError(): void
    {
        try {
            static::assertArrayMatched(['foo' => '1'], ['bar' => 2]);
            $this->fail();
        } catch (ExpectationFailedException $e) {
        }
    }

    public function testAssertStringMatched(): void
    {
        static::assertStringMatched('cu***mber', 'cucumber');
    }

    public function testAssertStringMatchedWithError(): void
    {
        try {
            static::assertStringMatched('cu***mber', 'potato');
            $this->fail();
        } catch (ExpectationFailedException $e) {
        }
    }
}
