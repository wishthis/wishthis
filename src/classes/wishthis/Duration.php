<?php

namespace wishthis;

class Duration
{
    public const HOUR    = 3600;
    public const DAY     = self::HOUR * 24;
    public const WEEK    = self::DAY * 7;
    public const MONTH   = self::DAY * 30;
    public const QUARTER = self::MONTH * 3;
    public const YEAR    = self::MONTH * 12;
}
