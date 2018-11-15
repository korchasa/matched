<?php declare(strict_types=1);

namespace korchasa\matched;

trait AssertMatchedTrait
{
    /**
     * Reports an error identified by $message if $actual JSON string is not matched $pattern.
     *
     * If both the presence and the value are important, then place the value in the pattern
     * If only the presence of a field is important, then place the "***" in the pattern
     * If both the presence and the value are NOT important, then do not place anything in pattern
     *
     * @example assertJsonMatched(
     *          '{
     *              "foo": "***", // check only presence
     *              "bar": 42 // check presence and value
     *          }',
     *          '{
     *              "foo": {
     *                  "baz": 1
     *              },
     *              "bar": 42,
     *              "baz": "foo" // do not check at all
     *          }'
     * )
     *
     * @param string $pattern
     * @param string $actual
     * @param string $message
     */
    private static function assertJsonMatched(string $pattern, string $actual, string $message = ''): void
    {
        static::assertThat($actual, new JsonConstraint($pattern), $message);
    }

    /**
     * Reports an error identified by $message if $actual array is not matched $pattern.
     *
     * If both the presence and the value are important, then place the value in the pattern
     * If only the presence of a field is important, then place the "***" in the pattern
     * If both the presence and the value are NOT important, then do not place anything in pattern
     *
     * @example assertArrayMatched(
     *          [
     *              "foo" => "***", // check only presence
     *              "bar" => 42 // check presence and value
     *          ],
     *          [
     *              "foo" => [
     *                  "baz" => 1
     *              ],
     *              "bar" => 42,
     *              "baz" => "foo" // do not check at all
     *          ]
     * )
     *
     * @param iterable $pattern
     * @param iterable $actual
     * @param string $message
     */
    private static function assertArrayMatched(iterable $pattern, iterable $actual, string $message = ''): void
    {
        static::assertThat((array)$actual, new ArrayConstraint((array)$pattern), $message);
    }

    /**
     * Reports an error identified by $message if $actual JSON string is not matched $pattern.
     *
     * If both the presence and the value are important, then place the value in the pattern
     * If only the presence of a field is important, then place the "***" in the pattern
     * If both the presence and the value are NOT important, then do not place anything in pattern
     *
     * @example assertStringMatched('cu***mber', 'cucumber')
     *
     * @param string $pattern
     * @param string $actual
     * @param string $message
     */
    private static function assertStringMatched(string $pattern, string $actual, string $message = ''): void
    {
        static::assertThat($actual, new StringConstraint($pattern), $message);
    }
}
