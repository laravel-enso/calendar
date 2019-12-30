<?php

namespace LaravelEnso\Calendar;

use LaravelEnso\Calendar\App\Enums\Frequencies;
use LaravelEnso\Calendar\App\Enums\UpdateType;
use LaravelEnso\Enums\EnumServiceProvider as ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    public $register = [
        'eventUpdateType' => UpdateType::class,
        'eventFrequencies' => Frequencies::class,
    ];
}
