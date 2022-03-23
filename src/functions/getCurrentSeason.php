<?php

/**
 * getWishlistNameSuggestion
 *
 * Returns the current season
 */

function getWishlistNameSuggestion(): string
{
    $now    = time();
    $month  = date('n');
    $season = '';

    $startOfEaster    = strtotime('15. April'); // Approximate
    $startOfChristmas = strtotime('24. December');

    if ($now <= $startOfEaster) {
        $season = 'Easter';
    } elseif ($now <= $startOfChristmas) {
        $season = 'Christmas';
    }

    return $season;
}
