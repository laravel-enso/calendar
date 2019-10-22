<?php

namespace LaravelEnso\Calendar\app\Traits;

use Carbon\Carbon;
use LaravelEnso\Calendar\app\Enums\Frequencies;

trait HasEvent
{
    public function frequence(): int
    {
        return Frequencies::Once;
    }

    public function recurrenceEnds(): ?Carbon
    {
        return null;
    }

    public function allDay(): bool
    {
        return true;
    }

    public function readonly(): bool
    {
        return true;
    }

    public function end(): Carbon
    {
        return $this->start();
    }

    public function body(): ?string
    {
        return null;
    }

    public function location(): ?string
    {
        return null;
    }
}
