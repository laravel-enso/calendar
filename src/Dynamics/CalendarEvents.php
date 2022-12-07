<?php

namespace LaravelEnso\Calendar\Dynamics;

use Closure;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\DynamicMethods\Contracts\Relation;
use LaravelEnso\Users\Models\User;

class CalendarEvents implements Relation
{
    public function bindTo(): array
    {
        return [User::class];
    }

    public function name(): string
    {
        return 'calendarEvents';
    }

    public function closure(): Closure
    {
        return fn (User $user) => $user
            ->belongsToMany(Event::class, 'calendar_event_user');
    }
}
