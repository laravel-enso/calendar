<?php

namespace LaravelEnso\Calendar;

use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Policies\EventPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Event::class => EventPolicy::class
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
