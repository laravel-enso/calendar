<?php

namespace LaravelEnso\Calendar;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Calendar\App\Dynamics\Methods\CalendarEvents;
use LaravelEnso\Calendar\App\Dynamics\Methods\Calendars;
use LaravelEnso\Core\App\Models\User;
use LaravelEnso\DynamicMethods\App\Services\Methods;

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
