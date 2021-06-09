<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/core/calendar')
    ->as('core.calendar.')
    ->group(function () {
        require __DIR__.'/app/calendar.php';
        require __DIR__.'/app/events.php';
    });
