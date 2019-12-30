<?php

namespace LaravelEnso\Calendar;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Calendar\App\Models\Calendar;
use LaravelEnso\Calendar\App\Models\Event;
use LaravelEnso\Core\App\Models\User;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        User::addDynamicMethod('calendarEvents', fn () => $this
            ->belongsToMany(Event::class, 'calendar_event_user')
        );

        User::addDynamicMethod('calendars', fn () => $this
            ->hasMany(Calendar::class, 'created_by')
        );
    }
}
