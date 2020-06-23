<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth', 'core'])
    ->namespace('LaravelEnso\Calendar\Http\Controllers')
    ->prefix('api/core/calendar')
    ->as('core.calendar.')
    ->group(function () {
        require 'app/calendar.php';
        require 'app/events.php';
    });
