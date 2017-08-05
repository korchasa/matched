<?php

use korchasa\matched\Match;

/**
 * @param string $pattern_json
 * @param $actual_json
 * @param string $any_symbol
 * @param callable|null $failure_callback
 * @return bool
 */
function match_json(
    string $pattern_json,
    $actual_json,
    string $any_symbol = Match::ANY_SYMBOL,
    callable $failure_callback = null
) :bool {
    return Match::json(...func_get_args());
}

/**
 * @param array $pattern
 * @param $actual
 * @param string $any_symbol
 * @param callable|null $failure_callback
 * @return bool
 */
function match_array(
    array $pattern,
    $actual,
    string $any_symbol = Match::ANY_SYMBOL,
    callable $failure_callback = null
) :bool {
    return Match::array(...func_get_args());
}

/**
 * @param array $pattern
 * @param $actual
 * @param string $any_symbol
 * @param callable|null $failure_callback
 * @return bool
 */
function match_string(
    string $pattern,
    $actual,
    string $any_symbol = Match::ANY_SYMBOL,
    callable $failure_callback = null
) :bool {
    return Match::string(...func_get_args());
}
