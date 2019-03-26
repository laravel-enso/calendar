<?php

namespace LaravelEnso\Calendar;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Calendar\app\Http\Responses\Events;
use LaravelEnso\Calendar\app\Contracts\ResolvesEvents;
use LaravelEnso\Calendar\app\Http\Responses\BaseEvents;

class AppServiceProvider extends ServiceProvider
{
    protected $resolvers = [];

    public function boot()
    {
        $this->loadDependencies();

        $this->offerPublishing();
    }

    private function loadDependencies()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->mergeConfigFrom(__DIR__.'/config/calendar.php', 'enso.calendar');
    }

    private function offerPublishing()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('laravel-enso'),
        ], 'events-config');

        $this->publishes([
            __DIR__.'/../stubs/CalendarServiceProvider.stub' => app_path('Providers/CalendarServiceProvider.php'),
        ], 'calendar-provider');
    }

    public function register()
    {
        $this->baseResolver();

        collect($this->resolvers)->each(function ($resolver) {
            Events::addResolver($resolver);
        });
    }

    public function baseResolver()
    {
        $this->app->bind(
            ResolvesEvents::class, BaseEvents::class
        );
    }
}
