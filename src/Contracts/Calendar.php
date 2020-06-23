<?php

namespace LaravelEnso\Calendar\Contracts;

interface Calendar
{
    public function getKey();

    public function name(): string;

    public function color(): string;

    public function private(): bool;

    public function readonly(): bool;
}
