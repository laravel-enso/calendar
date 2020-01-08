<?php

namespace LaravelEnso\Calendar;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Calendar\App\Dynamics\Relations\CalendarEvents;
use LaravelEnso\Calendar\App\Dynamics\Relations\Calendars;
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
