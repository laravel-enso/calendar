<?php

namespace LaravelEnso\Calendar;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Calendar\App\Contracts\Calendar;
use LaravelEnso\Calendar\App\Models\Event;
use LaravelEnso\Calendar\App\Policies\CalendarPolicy;
use LaravelEnso\Calendar\App\Policies\EventPolicy;

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
