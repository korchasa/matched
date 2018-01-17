<?php declare(strict_types=1);

namespace korchasa\matched;

class Match
{
    const ANY_SYMBOL = '***';
    public static $anySymbolBegin = '**>';
    public static $anySymbolEnd = '<**';

    /**
     * @param string $patternJson
     * @param mixed $actualJson
     * @param string $anySymbol
     * @param callable|null $failureCallback
     * @return bool
     * @throws \Exception
     */
    public static function json(
        string $patternJson,
        $actualJson,
        string $anySymbol = self::ANY_SYMBOL,
        callable $failureCallback = null
    ) :bool {
        $pattern = json_decode($patternJson, true);
        $actual  = json_decode($actualJson, true);

        return static::array($pattern, $actual, $anySymbol, $failureCallback);
    }

    public static function defaultJson(string $patternJson, string $anySymbol = self::ANY_SYMBOL): string
    {
        $pattern = json_decode($patternJson, true);

        return json_encode(static::defaultArray($pattern, $anySymbol), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array $pattern
     * @param mixed $actual
     * @param string $any_symbol
     * @param callable|null $failure_callback
     * @param string $keyPrefix
     * @return bool
     * @throws \Exception
     */
    public static function array(
        array $pattern,
        $actual,
        string $any_symbol = self::ANY_SYMBOL,
        callable $failure_callback = null,
        string $keyPrefix = ''
    ) :bool {
        if (!$failure_callback) {
            $failure_callback = function ($a, $b, $c) {
            };
        }

        if (!is_array($actual)) {
            $failure_callback($pattern, $actual, "Given value of `$keyPrefix` not a array");
            return false;
        }

        foreach ($pattern as $key => $value) {
            if (!array_key_exists($key, $actual)) {
                $failure_callback($pattern, $actual, "Given value has no key `$keyPrefix$key`");
                return false;
            }

            if (is_array($value)) {
                if (!static::array($value, $actual[$key], $any_symbol, $failure_callback, $keyPrefix.$key.'.')) {
                    return false;
                }
            } else {
                if (!static::isScalarEqual($value, $actual[$key], $any_symbol)) {
                    $failure_callback(
                        $value,
                        $actual[$key],
                        "Given value of `$keyPrefix$key` not match pattern `$value`"
                    );
                    return false;
                }
            }
        }

        return true;
    }

    public static function defaultArray(array $pattern, string $anySymbol = self::ANY_SYMBOL): array
    {
        return array_map(function ($value) use ($anySymbol) {
            if (is_string($value)) {
                return static::defaultString($value, $anySymbol);
            }
            if (is_array($value)) {
                return static::defaultArray($value, $anySymbol);
            }

            return $value;
        }, $pattern);
    }

    /**
     * @param string $pattern
     * @param mixed $actual
     * @param string $anySymbol
     * @param callable|null $failureCallback
     * @return bool
     * @throws \Exception
     */
    public static function string(
        string $pattern,
        $actual,
        string $anySymbol = self::ANY_SYMBOL,
        callable $failureCallback = null
    ) :bool {
        if (!$failureCallback) {
            $failureCallback = function ($a, $b, $c) {
            };
        }

        if (!is_string($actual)) {
            $failureCallback($pattern, $actual, 'Given value not a string');
            return false;
        }

        if ($pattern === $actual) {
            return true;
        }

        $pattern = self::replaceDefaultsWithAnySymbol($pattern, $anySymbol);

        $escapedPattern = preg_quote($pattern, '/');
        $escapedAnySymbol = preg_quote($anySymbol, '/');
        $result = preg_match('/'.str_replace($escapedAnySymbol, '.*', $escapedPattern).'/', $actual);

        if (false === $result) {
            throw new \RuntimeException(preg_last_error());
        }

        if (0 === $result) {
            $failureCallback($pattern, $actual, 'Given value not match pattern');
        }

        return (bool) $result;
    }

    /**
     * @param string $pattern
     * @param string $anySymbol
     * @return string
     */
    public static function defaultString(string $pattern, string $anySymbol = self::ANY_SYMBOL): string
    {
        return str_replace(
            [$anySymbol, self::$anySymbolBegin, self::$anySymbolEnd],
            '',
            $pattern
        );
    }

    /**
     * @param mixed $pattern
     * @param mixed $actual
     * @param string $anySymbol
     * @return bool
     * @throws \Exception
     */
    private static function isScalarEqual($pattern, $actual, string $anySymbol): bool
    {
        $pattern = static::replaceDefaultsWithAnySymbol($pattern, $anySymbol);

        if ($anySymbol === $pattern || $pattern === $actual) {
            return true;
        }

        if (!is_string($pattern)) {
            return false;
        }

        return static::string($pattern, $actual);
    }

    /**
     * @param mixed $pattern
     * @param string $anySymbol
     * @return mixed
     */
    private static function replaceDefaultsWithAnySymbol($pattern, string $anySymbol)
    {
        if (!is_string($pattern)) {
            return $pattern;
        }

        $beginDefaultSymbol = self::$anySymbolBegin;
        $endDefaultSymbol = self::$anySymbolEnd;
        if (0 === (substr_count($pattern, $beginDefaultSymbol) + substr_count($pattern, $endDefaultSymbol))) {
            return $pattern;
        }

        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $pattern = preg_replace(
            '/'.preg_quote($beginDefaultSymbol, '/').'(.*)'.preg_quote($endDefaultSymbol, '/').'/',
            $anySymbol,
            $pattern
        );
        if (null === $pattern) {
            throw new \InvalidArgumentException('Error on defaults processing: '.preg_last_error());
        }

        return $pattern;
    }
}
