<?php

use Illuminate\Support\Facades\Route;

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
