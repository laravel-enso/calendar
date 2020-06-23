<?php

namespace LaravelEnso\Calendar\Contracts;

use LaravelEnso\Calendar\DTOs\Route;

interface Routable
{
    public function route(): Route;
}
