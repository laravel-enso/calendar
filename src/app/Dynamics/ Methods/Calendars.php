<?php

namespace LaravelEnso\Calendar\App\Dynamics\Methods;

use Closure;
use LaravelEnso\Calendar\App\Models\Calendar;
use LaravelEnso\Calendar\App\Models\Event;
use LaravelEnso\DynamicMethods\App\Contracts\Method;

class Calendars implements Method
{
    public function name(): string
    {
        return 'calendars';
    }

    public function closure(): Closure
    {
        return fn () => $this
            ->hasMany(Calendar::class, 'created_by');
    }
}
