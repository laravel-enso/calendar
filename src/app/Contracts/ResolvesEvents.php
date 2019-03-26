<?php

namespace LaravelEnso\Calendar\app\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ResolvesEvents
{
    public function get(Request $request): Collection;
}
