<?php

namespace LaravelEnso\Calendar;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Calendar\app\Models\Calendar;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Core\app\Models\User;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
<<<<<<< HEAD
        User::addDynamicMethod('calendarEvents', fn () => $this
            ->belongsToMany(Event::class, 'calendar_event_user')
        );

        User::addDynamicMethod('calendars', fn () => $this
            ->hasMany(Calendar::class, 'created_by')
        );
=======
        User::addDynamicMethod('calendarEvents', fn () => (
            $this->belongsToMany(Event::class, 'calendar_event_user')
        ));

        User::addDynamicMethod('calendars', fn () => (
            $this->hasMany(Calendar::class, 'created_by')
        ));
>>>>>>> a95e605a835a0824cbeeef69eecb8491b0ff0d5a
    }
}
