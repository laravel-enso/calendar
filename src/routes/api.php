<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\Calendar\app\Http\Controllers')
    ->prefix('api/core/calendar')->as('core.calendar.')
    ->group(function () {
        Route::resource('events', 'EventController')
            ->except('show');
        // Route::resource('reminders', 'ReminderController', ['only' => ['store', 'update', 'destroy']]);
    });
