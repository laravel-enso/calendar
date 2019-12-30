<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\Calendar\App\Http\Controllers')
    ->prefix('api/core/calendar')
    ->as('core.calendar.')
    ->group(function () {
        require 'app/calendar.php';
        require 'app/events.php';
    });
