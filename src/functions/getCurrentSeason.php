<?php

/**
 * getCurrentSeason
 *
 * Returns the current season
 */

function getCurrentSeason(): string
{
    $now = time();
    $month = date('n');
    $season = '';

    $startOfYear      = strtotime('01. January');
    $startOfEaster    = strtotime('15. April'); // Approximate
    $startOfChristmas = strtotime('24. December');

    if ($now <= $startOfEaster) {
        $season = 'Easter';
    } elseif ($now <= $startOfChristmas) {
        $season = 'Christmas';
    }

    return $season;
}
