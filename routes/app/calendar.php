<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\Calendar\Http\Controllers\Calendar\Index;
use LaravelEnso\Calendar\Http\Controllers\Calendar\Create;
use LaravelEnso\Calendar\Http\Controllers\Calendar\Store;
use LaravelEnso\Calendar\Http\Controllers\Calendar\Edit;
use LaravelEnso\Calendar\Http\Controllers\Calendar\Update;
use LaravelEnso\Calendar\Http\Controllers\Calendar\Destroy;
use LaravelEnso\Calendar\Http\Controllers\Calendar\Options;

Route::get('', Index::class)->name('index');
Route::get('create', Create::class)->name('create');
Route::post('', Store::class)->name('store');
Route::get('{calendar}/edit', Edit::class)->name('edit');
Route::patch('{calendar}', Update::class)->name('update');
Route::delete('{calendar}', Destroy::class)->name('destroy');
Route::get('options', Options::class)->name('options');
