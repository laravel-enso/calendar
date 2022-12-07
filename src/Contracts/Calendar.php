<?php

namespace LaravelEnso\Calendar\Contracts;

use LaravelEnso\Calendar\Enums\Color;

interface Calendar
{
    public function getKey();

    public function name(): string;

    public function color(): Color;

    public function private(): bool;

    public function readonly(): bool;
}
