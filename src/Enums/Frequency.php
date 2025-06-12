<?php

namespace LaravelEnso\Calendar\Enums;

use LaravelEnso\Enums\Contracts\Frontend;
use LaravelEnso\Enums\Contracts\Select;
use LaravelEnso\Enums\Traits\Select as Options;

enum Frequency: int implements Select, Frontend
{
    use Options;
    case Once = 1;
    case Daily = 2;
    case Weekdays = 3;
    case Weekly = 4;
    case Monthly = 5;
    case Yearly = 6;

    public static function registerBy(): string
    {
        return 'eventFrequencies';
    }
}
