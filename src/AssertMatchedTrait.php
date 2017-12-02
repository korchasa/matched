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
     * @param string|object $pattern
     * @param string|object $actual
     * @param string        $message
     */
    public static function assertJsonMatched($pattern, $actual, string $message = '')
    {
        static::assertJson((string) $pattern, 'Pattern must be a valid JSON');
        static::assertJson((string) $actual, 'Actual JSON must be a valid JSON');

        $constraint = new JsonConstraint((string) $pattern);

        static::assertThat((string) $actual, $constraint, $message);
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
     * @param array|object $pattern
     * @param array|object $actual
     * @param string        $message
     */
    public static function assertArrayMatched($pattern, $actual, string $message = '')
    {
        static::assertInternalType('array', (array) $pattern, 'Pattern must be a array');
        static::assertInternalType('array', (array) $actual, 'Actual must be a array');

        $constraint = new ArrayConstraint((array) $pattern);

        static::assertThat((array) $actual, $constraint, $message);
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
     * @param string|object $pattern
     * @param string|object $actual
     * @param string        $message
     */
    public static function assertStringMatched($pattern, $actual, string $message = '')
    {
        static::assertInternalType('string', (string) $pattern, 'Pattern must be a string');
        static::assertInternalType('string', (string) $actual, 'Actual must be a string');

        $constraint = new StringConstraint((string) $pattern);

        static::assertThat((string) $actual, $constraint, $message);
    }
}
