<?php

/**
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

/**
 * Query string to key value pair
 *
 * @return array
 */
function query_to_key_value_pair(string $query): array
{
    $query            = str_contains($query, '?') ? parse_url($query, PHP_URL_QUERY) : $query;
    $parameters_pairs = explode('&', $query);
    $parameters       = array();

    foreach ($parameters_pairs as $index => $pair) {
        $parts = explode('=', $pair);
        $key   = reset($parts);
        $value = end($parts);

        $parameters[$key] = $value;
    }

    return $parameters;
}
