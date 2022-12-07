<?php

namespace LaravelEnso\Calendar\Enums;

use LaravelEnso\Enums\Contracts\Frontend;

enum UpdateType: int implements Frontend
{
    case OnlyThis = 1;
    case ThisAndFuture = 2;
    case All = 3;

    public static function registerBy(): string
    {
        return 'eventUpdateType';
    }
}
