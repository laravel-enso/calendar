<?php

namespace LaravelEnso\Calendar\App\Dynamics\Methods;

use Closure;
use LaravelEnso\Calendar\App\Models\Event;
use LaravelEnso\DynamicMethods\App\Contracts\Method;

class CalendarEvents implements Method
{
    public function name(): string
    {
        return 'calendarEvents';
    }

    public function closure(): Closure
    {
        return fn () => $this
            ->belongsToMany(Event::class, 'calendar_event_user');
    }
}
