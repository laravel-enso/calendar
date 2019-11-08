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
        User::addDynamicMethod('calendarEvents', function () {
            return $this->belongsToMany(Event::class, 'calendar_event_user');
        });

        User::addDynamicMethod('calendars', function () {
            return $this->hasMany(Calendar::class, 'created_by');
        });
    }
}
