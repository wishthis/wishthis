<?php

namespace wishthis;

enum Navigation: int
{
    case Wishlists = 1;
    case Blog      = 2;
    case System    = 3;
    case Settings  = 4;
    case Account   = 5;
    case Login     = 6;
    case Register  = 7;
}
