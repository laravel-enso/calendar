<?php

namespace LaravelEnso\Calendar\app\Contracts;

use LaravelEnso\Calendar\app\DTOs\Route;

interface Routable
{
    public function route(): Route;
}
