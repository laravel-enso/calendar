<?php

namespace LaravelEnso\Calendar\Dynamics\Relations;

use Closure;
use LaravelEnso\Calendar\Models\Calendar;
use LaravelEnso\DynamicMethods\Contracts\Method;

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
