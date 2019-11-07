<?php

namespace LaravelEnso\Calendar;

use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Contracts\Calendar;
use LaravelEnso\Calendar\app\Policies\EventPolicy;
use LaravelEnso\Calendar\app\Policies\CalendarPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
