<?php

namespace LaravelEnso\Calendar;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Calendar\Facades\Calendars;
use LaravelEnso\Mediator\Contracts\ClientServiceProvider;
use LaravelEnso\Mediator\Contracts\MediatorServiceProvider as Contract;
use LaravelEnso\Mediator\MediatorServiceProvider;

class CalendarServiceProvider extends ServiceProvider implements Contract
{
    public function register()
    {
        MediatorServiceProvider::add($this);
    }

    public function handle(ClientServiceProvider $provider)
    {
        Calendars::register($provider->data());
    }
}
