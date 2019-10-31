<?php

namespace LaravelEnso\Calendar;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Models\Calendar;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        User::addDynamicMethod('calendarEvents', function () {
            return $this->hasMany(Event::class, 'created_by');
        });
        User::addDynamicMethod('calendars', function () {
            return $this->hasMany(Calendar::class, 'created_by');
        });
    }
}