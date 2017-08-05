<?php

namespace korchasa\matched;

trait AssertMatchedTrait
{


    /**
     * Проверка для частичного сравнения json
     *
     * Если важно и наличи и содержимое, то в петтерне указывается значение
     * Если важно наличие узла, но не важно содержимое, то в паттерне указывается "*any*"
     * Если не важно ни наличие, ни содержимое элемента, то его не нужно указывать в паттерне
     *
     * @example assertJsonMatched(
     *          '{
     *              "foo": "*any*", - проверять только наличие
     *              "bar": 42 - проверять и наличие и значение
     *          }',
     *          '{
     *              "foo": {
     *                  "baz": 1
     *              },
     *              "bar": 42,
     *              "baz": "foo" - не проверяется вообще
     *          }'
     * )
     *
     * @param string|object $pattern
     * @param string|object $actual
     * @param string        $message
     */
    public static function assertJsonMatched($pattern, $actual, $message = '')
    {
        self::assertJson((string) $pattern, 'Pattern must be a valid JSON');
        self::assertJson((string) $actual, 'Actual JSON must be a valid JSON');

        $constraint = new JsonMatchedConstraint((string) $pattern);

        self::assertThat((string) $actual, $constraint, $message);
    }//end assertJsonMatched()
}
