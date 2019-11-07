<?php

namespace LaravelEnso\Calendar;

use LaravelEnso\Calendar\app\Enums\UpdateType;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Enums\EnumServiceProvider as ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public $register = [
        'eventUpdateType' => UpdateType::class,
        'eventFrequencies' => Frequencies::class,
    ];
}
