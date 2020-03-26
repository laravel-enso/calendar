<?php

namespace LaravelEnso\Calendar\App\Contracts;

use LaravelEnso\Calendar\App\DTOs\Route;

interface Routable
{
    public function route(): Route;
}
