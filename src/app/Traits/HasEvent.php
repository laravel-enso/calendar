<?php

namespace LaravelEnso\Calendar\app\Traits;

use Carbon\Carbon;
use LaravelEnso\Calendar\app\Enums\Frequencies;

trait HasEvent
{
    public function frequence()
    {
        return Frequencies::Once;
    }

    public function recurrenceEnds(): ?Carbon
    {
        return null;
    }

    public function allDay()
    {
        return false;
    }

    public function readonly()
    {
        return true;
    }

    public function end(): Carbon
    {
        return $this->start();
    }
}
