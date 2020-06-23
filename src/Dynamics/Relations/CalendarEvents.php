<?php

namespace LaravelEnso\Calendar\Dynamics\Relations;

use Closure;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\DynamicMethods\Contracts\Method;

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
