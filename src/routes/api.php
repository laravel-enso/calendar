<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\Calendar\app\Http\Controllers')
    ->prefix('api/core/calendar')
    ->as('core.calendar.')
    ->group(function () {
        Route::namespace('Calendar')
            ->group(function () {
                Route::get('', 'Index')->name('index');
                Route::get('create', 'Create')->name('create');
                Route::post('', 'Store')->name('store');
                Route::get('{calendar}/edit', 'Edit')->name('edit');
                Route::patch('{calendar}', 'Update')->name('update');
                Route::delete('{calendar}', 'Destroy')->name('destroy');
                Route::get('options', 'Options')->name('options');

            });

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
    });
