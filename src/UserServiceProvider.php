<?php

namespace LaravelEnso\Calendar;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Calendar\Dynamics\Relations\CalendarEvents;
use LaravelEnso\Calendar\Dynamics\Relations\Calendars;
use LaravelEnso\DynamicMethods\Services\Methods;
use LaravelEnso\Users\Models\User;

class UserServiceProvider extends ServiceProvider
{
    private array $methods = [
        CalendarEvents::class,
        Calendars::class,
    ];

    public function boot()
    {
        Methods::bind(User::class, $this->methods);
    }
}
