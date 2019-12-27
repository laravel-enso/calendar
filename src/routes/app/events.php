<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Events')
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
