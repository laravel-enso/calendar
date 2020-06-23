<?php

namespace LaravelEnso\Calendar;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Calendar\Contracts\Calendar;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\Calendar\Policies\CalendarPolicy;
use LaravelEnso\Calendar\Policies\EventPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Event::class => EventPolicy::class,
        Calendar::class => CalendarPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
