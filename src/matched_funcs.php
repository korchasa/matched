<?php

use korchasa\matched\Matched;

/**
 * @param string $pattern_json
 * @param $actual_json
 * @param string $any_symbol
 * @param callable|null $failure_callback
 * @return bool
 */
function matched_json(
    string $pattern_json,
    $actual_json,
    string $any_symbol = Matched::ANY_SYMBOL,
    callable $failure_callback = null
) :bool {
    return Matched::json(...func_get_args());
}

/**
 * @param array $pattern
 * @param $actual
 * @param string $any_symbol
 * @param callable|null $failure_callback
 * @return bool
 */
function matched_array(
    array $pattern,
    $actual,
    string $any_symbol = Matched::ANY_SYMBOL,
    callable $failure_callback = null
) :bool {
    return Matched::array(...func_get_args());
}

/**
 * @param array $pattern
 * @param $actual
 * @param string $any_symbol
 * @param callable|null $failure_callback
 * @return bool
 */
function matched_string(
    string $pattern,
    $actual,
    string $any_symbol = Matched::ANY_SYMBOL,
    callable $failure_callback = null
) :bool {
    return Matched::string(...func_get_args());
}
