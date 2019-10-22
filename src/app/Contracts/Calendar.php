<?php

namespace LaravelEnso\Calendar\app\Contracts;

use Illuminate\Support\Collection;
use LaravelEnso\Calendar\app\Services\Request;

interface Calendar
{
    public static function events(Request $request): Collection;

    public function getKey();

    public function name(): string;

    public function color(): string;

    public function readonly(): bool;
}
