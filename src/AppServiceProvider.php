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
        $this->loadDependencies()
            ->publisDependencies();
    }

    private function loadDependencies()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->mergeConfigFrom(__DIR__.'/config/calendar.php', 'enso.calendar');

        return $this;
    }

    private function publisDependencies()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'calendar-config');
       
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'enso-config');

        $this->publishes([
            __DIR__.'/../stubs/CalendarServiceProvider.stub' =>
                app_path('Providers/CalendarServiceProvider.php'),
        ], 'calendar-provider');
    }

    public function register()
    {
        $this->app->bind(
            ResolvesEvents::class,
            BaseEvents::class
        );

        collect($this->resolvers)->each(function ($resolver) {
            Events::addResolver($resolver);
        });
    }
}
