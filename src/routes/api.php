<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\Calendar\app\Http\Controllers')
    ->prefix('api/core/calendar')
    ->as('core.calendar.')
    ->group(function () {
        Route::namespace('Event')
            ->prefix('events')
            ->as('events.')
            ->group(function () {
                Route::get('', 'Index')->name('index');
                Route::get('create', 'Create')->name('create');
                Route::post('', 'Store')->name('store');
                Route::get('{event}/edit', 'Edit')->name('edit');
                Route::patch('{event}', 'Update')->name('update');
                Route::delete('{event}', 'Destroy')->name('destroy');
            });

        // Route::namespace('Reminder')
        //      ->prefix('reminders')
        //     ->as('reminders.')
        //     ->group(function() {
        //         Route::get('', 'Index')->name('index');
        //         Route::get('create', 'Create')->name('create');
        //         Route::post('', 'Store')->name('store');
        //         Route::get('{event}/edit', 'Edit')->name('edit');
        //         Route::patch('{event}', 'Update')->name('update');
        //         Route::delete('{event}', 'Destroy')->name('destroy');
        //     });
    });
