<?php

namespace LaravelEnso\Calendar;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use LaravelEnso\Calendar\app\Commands\Notify;
use LaravelEnso\Calendar\app\Services\Calendars;

class AppServiceProvider extends ServiceProvider
{
    protected $resolvers = [];

    public $singletons = [
        'calendars' => Calendars::class,
    ];

    public function boot()
    {
        $this->commands(Notify::class);

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('enso:calendar:notify')->everyMinute();
        });

        $this->loadDependencies()
            ->publisDependencies();
    }

    private function loadDependencies()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-enso/calendar');

        return $this;
    }

    private function publisDependencies()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'enso-config');

        $this->publishes([
            __DIR__.'/../stubs/CalendarServiceProvider.stub' => app_path('Providers/CalendarServiceProvider.php'),
        ], 'calendar-provider');

        $this->publishes([
            __DIR__.'/database/factories' => database_path('factories'),
        ], 'calendar-factory');

        $this->publishes([
            __DIR__.'/database/factories' => database_path('factories'),
        ], 'enso-factories');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-enso/calendar'),
        ], 'calendar-email-template');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-enso/calendar'),
        ], 'enso-mail');
    }
}
