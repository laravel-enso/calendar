<?php

namespace LaravelEnso\Calendar;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Calendar\app\Commands\SendReminders;
use LaravelEnso\Calendar\app\Services\Calendars;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        'calendars' => Calendars::class,
    ];

    public function boot()
    {
        $this->commands(SendReminders::class);

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('enso:calendar:send-reminders')->everyMinute();
        });

        $this->loadDependencies()
            ->publishDependencies();
    }

    private function loadDependencies()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-enso/calendar');

        return $this;
    }

    private function publishDependencies()
    {
        $this->publishes([
            __DIR__.'/../stubs/CalendarServiceProvider.stub' => app_path('Providers/CalendarServiceProvider.php'),
        ], 'calendar-provider');

        $this->publishes([
            __DIR__.'/database/factories' => database_path('factories'),
        ], 'calendar-factories');

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
