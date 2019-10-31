<?php

namespace LaravelEnso\Calendar\app\Contracts;

use LaravelEnso\Calendar\app\DTOs\Route;

interface RoutableEvent
{
    public function route(): Route;
}
