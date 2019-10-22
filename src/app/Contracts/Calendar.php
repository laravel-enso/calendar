<?php

namespace LaravelEnso\Calendar\app\Contracts;

use Illuminate\Support\Collection;
use LaravelEnso\Calendar\app\Services\Request;

interface Calendar
{
    public static function getEvents(Request $request): Collection;

    public function getKey();

    public function name();

    public function color();

    public function readonly();
}
