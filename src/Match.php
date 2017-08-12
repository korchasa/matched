<?php

namespace korchasa\matched;

class Match
{
    const ANY_SYMBOL = '***';

    /**
     * @param string $pattern_json
     * @param $actual_json
     * @param string $any_symbol
     * @param callable|null $failure_callback
     * @return bool
     */
    public static function json(
        string $pattern_json,
        $actual_json,
        string $any_symbol = self::ANY_SYMBOL,
        callable $failure_callback = null
    ) :bool {
        $pattern = json_decode($pattern_json, true);
        $actual  = json_decode($actual_json, true);

        return static::array($pattern, $actual, $any_symbol, $failure_callback);
    }

    /**
     * @param array $pattern
     * @param $actual
     * @param string $any_symbol
     * @param callable|null $failure_callback
     * @return bool
     */
    public static function array(
        array $pattern,
        $actual,
        string $any_symbol = self::ANY_SYMBOL,
        callable $failure_callback = null
    ) :bool {
        if (!$failure_callback) {
            $failure_callback = function ($a, $b, $c) {
            };
        }

        if (!is_array($actual)) {
            $failure_callback($pattern, $actual, 'Given value not a array');
            return false;
        }

        if (count($pattern) > count($actual)) {
            $failure_callback($pattern, $actual, 'Pattern size greater than given value');
            return false;
        }

        foreach ($pattern as $key => $value) {
            if (!array_key_exists($key, $actual)) {
                $failure_callback($pattern, $actual, "Given value has no key `$key`");
                return false;
            }

            if (is_array($value)) {
                if (!static::array($value, $actual[$key], $any_symbol, $failure_callback)) {
                    return false;
                }
            } else {
                if (!static::isScalarEqual($value, $actual[$key], $any_symbol)) {
                    $failure_callback($value, $actual[$key], "Given value of `$value` not match pattern");
                    return false;
                }
            }
        }

        return true;
    }

    public static function string(
        string $pattern,
        $actual,
        string $any_symbol = self::ANY_SYMBOL,
        callable $failure_callback = null
    ) :bool {
        if (!$failure_callback) {
            $failure_callback = function ($a, $b, $c) {
            };
        }

        if (!is_string($actual)) {
            $failure_callback($pattern, $actual, 'Given value not a string');
            return false;
        }

        if ($pattern === $actual) {
            return true;
        }

        $escapedAnySymbol = preg_quote($any_symbol, '/');
        $escapedPattern = preg_quote($pattern, '/');
        $result = preg_match('/'.str_replace($escapedAnySymbol, '.*', $escapedPattern).'/', $actual);

        if (false === $result) {
            throw new \Exception(preg_last_error());
        }

        if (0 === $result) {
            $failure_callback($pattern, $actual, 'Given value not match pattern');
        }

        return (bool) $result;
    }

    private static function isScalarEqual($pattern, $actual, $anySymbol)
    {
        if ($anySymbol === $pattern || $pattern === $actual) {
            return true;
        }

        if (is_string($pattern) && false !== strpos($pattern, $anySymbol) && static::string($pattern, $actual)) {
            return true;
        }

        return false;
    }
}
