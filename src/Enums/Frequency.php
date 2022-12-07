<?php

namespace LaravelEnso\Calendar\Enums;

use LaravelEnso\Calendar\Services\Frequency\Types\Daily;
use LaravelEnso\Calendar\Services\Frequency\Types\Monthly;
use LaravelEnso\Calendar\Services\Frequency\Types\Once;
use LaravelEnso\Calendar\Services\Frequency\Types\Weekday;
use LaravelEnso\Calendar\Services\Frequency\Types\Weekly;
use LaravelEnso\Calendar\Services\Frequency\Types\Yearly;
use LaravelEnso\Enums\Contracts\Frontend;

enum Frequency: int implements Frontend
{
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

    public function service(): string
    {
        return match ($this) {
            self::Once     => Once::class,
            self::Daily    => Daily::class,
            self::Weekly   => Weekly::class,
            self::Weekdays => Weekday::class,
            self::Monthly  => Monthly::class,
            self::Yearly   => Yearly::class,
        };
    }
}
